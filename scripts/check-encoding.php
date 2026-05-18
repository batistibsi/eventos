<?php

declare(strict_types=1);

$root = realpath($argv[1] ?? __DIR__ . '/..');

if ($root === false || !is_dir($root)) {
	fwrite(STDERR, "Diretorio invalido.\n");
	exit(1);
}

$write = in_array('--write', $argv, true);
$extensions = array_flip([
	'php', 'phtml', 'js', 'css', 'html', 'xml', 'json', 'md', 'txt', 'ini', 'sql'
]);

$skipDirs = [
	DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR,
	DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR,
	DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR,
	DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR,
	DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR,
	DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR,
];

$suspiciousPattern = '/Ã¡|Ã |Ã¢|Ã£|Ã¤|Ã�|Ã€|Ã‚|Ã„|Ã©|Ã¨|Ãª|Ã«|Ã‰|Ãˆ|ÃŠ|Ã‹|Ã­|Ã¬|Ã®|Ã¯|Ã�|ÃŒ|ÃŽ|Ã�|Ã³|Ã²|Ã´|Ãµ|Ã¶|Ã“|Ã’|Ã”|Ã•|Ã–|Ãº|Ã¹|Ã»|Ã¼|Ãš|Ã™|Ã›|Ãœ|Ã§|Ã‡|Ã±|Ã‘|ÃƒÂ|Â[^\r\n\t ]|â€“|â€”|â€œ|â€\x9d|â€\x99|�/u';
$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

$found = [];
$fixed = [];

foreach ($iterator as $file) {
	if (!$file->isFile()) {
		continue;
	}

	$path = $file->getPathname();
	$normalized = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
	$skip = false;

	foreach ($skipDirs as $skipDir) {
		if (strpos($normalized, $skipDir) !== false) {
			$skip = true;
			break;
		}
	}

	if ($skip) {
		continue;
	}

	$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
	if (!isset($extensions[$extension])) {
		continue;
	}

	$content = file_get_contents($path);
	if ($content === false) {
		continue;
	}

	$issues = [];

	if (!preg_match('//u', $content)) {
		$issues[] = 'UTF-8 invalido';
	}

	if (preg_match_all($suspiciousPattern, $content, $matches) > 0) {
		$issues[] = 'Possivel mojibake (' . count($matches[0]) . ' ocorrencias)';
	}

	if (!$issues) {
		continue;
	}

	$relative = ltrim(str_replace($root, '', $path), DIRECTORY_SEPARATOR);
	$found[$relative] = $issues;

	if (!$write) {
		continue;
	}

	$repaired = repairMojibake($content, $suspiciousPattern);
	if ($repaired !== $content) {
		$backup = $path . '.bak-encoding';
		if (!file_exists($backup)) {
			file_put_contents($backup, $content);
		}
		file_put_contents($path, $repaired);
		$fixed[] = $relative;
	}
}

if (!$found) {
	echo "Nenhum problema de encoding encontrado.\n";
	exit(0);
}

echo "Arquivos com possivel problema de encoding:\n";
foreach ($found as $file => $issues) {
	echo ' - ' . $file . ': ' . implode('; ', $issues) . "\n";
}

if ($write) {
	echo "\nArquivos regravados com backup .bak-encoding:\n";
	foreach ($fixed as $file) {
		echo ' - ' . $file . "\n";
	}
}

exit($write && !$fixed ? 2 : 0);

function repairMojibake(string $content, string $pattern): string
{
	$current = $content;
	$currentScore = suspiciousScore($current, $pattern);

	for ($i = 0; $i < 3; $i++) {
		$candidates = [];

		$latin1 = @iconv('ISO-8859-1', 'UTF-8//IGNORE', $current);
		if ($latin1 !== false) {
			$candidates[] = $latin1;
		}

		$cp1252 = @iconv('Windows-1252', 'UTF-8//IGNORE', $current);
		if ($cp1252 !== false) {
			$candidates[] = $cp1252;
		}

		$best = $current;
		$bestScore = $currentScore;

		foreach ($candidates as $candidate) {
			$score = suspiciousScore($candidate, $pattern);
			if ($score < $bestScore && preg_match('//u', $candidate)) {
				$best = $candidate;
				$bestScore = $score;
			}
		}

		if ($bestScore >= $currentScore) {
			break;
		}

		$current = $best;
		$currentScore = $bestScore;
	}

	return $current;
}

function suspiciousScore(string $content, string $pattern): int
{
	$score = 0;

	if (!preg_match('//u', $content)) {
		$score += 1000;
	}

	if (preg_match_all($pattern, $content, $matches) > 0) {
		$score += count($matches[0]);
	}

	return $score;
}
