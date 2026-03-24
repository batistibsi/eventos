<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Instituto ACIM | Responsabilidade Social</title>

<link rel="icon" href="./favicon.ico">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
color:#1f2937;
line-height:1.6;
}

/* HEADER */

header{
position:fixed;
top:0;
width:100%;
background:white;
box-shadow:0 2px 10px rgba(0,0,0,0.08);
z-index:1000;
}

.container{
width:90%;
max-width:1200px;
margin:auto;
}

nav{
display:flex;
align-items:center;
justify-content:space-between;
padding:18px 0;
}

.logo img{
height:45px;
}

.menu a{
margin-left:25px;
text-decoration:none;
color:#1f2937;
font-weight:500;
}

.menu a:hover{
color:#0ea5a4;
}

/* HERO CAROUSEL */

.hero{
margin-top:80px;
position:relative;
height:500px;
overflow:hidden;
}

.slide{
position:absolute;
width:100%;
height:100%;
background-size:cover;
background-position:center;
opacity:0;
transition:opacity 1s;
}

.slide.active{
opacity:1;
}

.hero-text{
position:absolute;
bottom:60px;
left:50%;
transform:translateX(-50%);
color:white;
text-align:center;
background:rgba(0,0,0,0.4);
padding:20px 40px;
border-radius:10px;
}

.hero-text h1{
font-size:36px;
}

/* SECTION */

section{
padding:80px 0;
}

.section-title{
text-align:center;
margin-bottom:50px;
}

.section-title h2{
font-size:32px;
color:#14454E;
}

.section-title h1{
font-size:32px;
color:#ffffff;
}

.section-title h4{
font-size:18px;
color:#ffffff;
}


/* SOBRE */

.sobre-1{
background:#f5f7fa;
}

.sobre p{
max-width:800px;
margin:auto;
text-align:center;
}

/* PILARES */

.pilares-1{
background-color: rgb(8, 68, 68);
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:25px;
}

.card{
background:white;
padding:30px;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.card h3{
color:#0f3d4c;
margin-bottom:10px;
}

/* ATUAÇÃO */

.atuacao{
background:#f5f7fa;
}

/* CERTIFICAÇÃO */

.certificacao{
background-color: rgb(8, 68, 68);
/* background:#0f3d4c; 8,68,68 */
color:white;
}

.certificacao p{
margin:auto;
margin-bottom:15px;
text-align:center;
max-width:800px;
}

.btn{
display:inline-block;
background:#ff7a18;
color:white;
padding:14px 30px;
border-radius:30px;
text-decoration:none;
margin:auto;
margin-top:20px;
}

/* CONTATO */

.contato{
background:#f5f7fa;
text-align:center;
}

.redes a{
margin:10px;
display:inline-block;
color:#0f3d4c;
text-decoration:none;
font-weight:500;
}


/* https://www.freepik.com/icon/facebook_3955011#fromView=search&page=1&position=6&uuid=6eaf609a-2853-47ad-a153-9111449f348d */
/* https://www.freepik.com/icon/linkedin_4494498#fromView=search&page=1&position=3&uuid=6eaf609a-2853-47ad-a153-9111449f348d */
/* https://www.freepik.com/icon/social_15707749#fromView=search&page=1&position=19&uuid=6eaf609a-2853-47ad-a153-9111449f348d */


.face img{
height:15px;
}

.lkd img{
height:15px;
}

.insta img{
height:15px;
}


/* FOOTER */

footer{
background:rgb(8, 68, 68);
color:white;
text-align:center;
padding:25px;
font-size:14px;
}

/* RESPONSIVO */

@media(max-width:768px){

.menu{
display:none;
}

.hero-text h1{
font-size:26px;
}

}

</style>
</head>

<body>

<header>
<div class="container">
<nav>

<div class="logo">
<img src="./images/logo_header.png">
</div>

<div class="menu">
<a href="#quem">Quem Somos</a>
<a href="#atuacao">Onde Atuamos</a>
<a href="#certificacao">Certificação Impactacim</a>
<a href="#contato">Contato</a>
</div>

</nav>
</div>
</header>

<!-- HERO -->

<section class="hero">

<div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d')"></div>

<div class="slide" style="background-image:url('https://images.unsplash.com/photo-1509099836639-18ba1795216d')"></div>

<div class="slide" style="background-image:url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f')"></div>

<div class="hero-text">
<h1>INSTITUTO ACIM Responsabilidade Social que transforma Maringá</h1>
</div>

</section>

<!-- QUEM SOMOS -->

<section id="sobre-1" class="sobre">

<div class="container">

<div class="section-title">
<h2>Instituto ACIM</h2>
</div>

<p>
O Instituto ACIM é uma entidade sem fins lucrativos vinculada à Associação Comercial de Maringá, com o propósito de fomentar um ecossistema capaz de gerar resultados de alto impacto social e ambiental de forma sustentável.
</p>

</div>

</section>

<!-- PILARES -->

<section id="pilares-1" class="pilares-1">

<div class="container">

<div class="section-title">
<h1>Pilares Culturais</h1>
</div>

<div class="grid">

<div class="card">
<h3>Propósito</h3>
<p>Fomentar um ecossistema para gerar resultados de alto impacto social e ambiental.</p>
</div>

<div class="card">
<h3>Missão</h3>
<p>Sensibilizar empresas para agirem com responsabilidade social.</p>
</div>

<div class="card">
<h3>Visão</h3>
<p>Tornar Maringá reconhecida nacionalmente pela atuação socialmente responsável.</p>
</div>

<div class="card">
<h3>Valores</h3>
<p>Protagonismo, representatividade, impacto social e compromisso.</p>
</div>

</div>

</div>

</section>

<!-- ONDE ATUAMOS -->

<section id="atuacao" class="atuacao">

<div class="container">

<div class="section-title">
<h2>Onde Atuamos</h2>
</div>

<div class="grid">

<div class="card">
<h3>Filantrópico</h3>
<p>Nosso compromisso com o bem-estar social através de ações e iniciativas.</p>
</div>

<div class="card">
<h3>Ambiental</h3>
<p>Iniciativas que ajudam a minimizar o impacto ambiental.</p>
</div>

<div class="card">
<h3>Esportivo</h3>
<p>Promoção de atividades saudáveis e integração comunitária.</p>
</div>

<div class="card">
<h3>Sustentabilidade</h3>
<p>Projetos que promovem um futuro sustentável para a comunidade.</p>
</div>

</div>

</div>

</section>

<!-- CERTIFICAÇÃO -->

<section id="certificacao" class="certificacao">

<div class="container">

<div class="section-title">
<h1>Certificação IMPACTACIM</h1>
</div>

<p>A Certificação IMPACTACIM reconhece práticas ESG realizadas por empresas e organizações brasileiras.
Empresas podem submeter de 1 a 15 práticas relacionadas aos pilares ESG:</p>
<h4><p>ambiental, social e governança.</p></h4>
<p>As orgnizações podem ser classificadas em quatro categorias: Iniciante, Bronze, Prata e Ouro</p>


<h3><p>Quer fazer parte?</p></h3>


<p><a class="btn" href="./formulario_inscricao.php" target="_blank">
Participar da Certificação
</a></p>

</div>

</section>

<!-- CONTATO -->

<section id="contato" class="contato">

<div class="container">

<div class="section-title">
<h2>Contato</h2>
</div>

<p>R. Ver. Basílio Sautchuk, 388 - Zona 01, Maringá - PR</p>
<p>Email: gestao@institutoacim.org.br</p>
<p>Telefone: (44) 99910-0938</p>

<div class="redes">

<a href="https://instagram.com/instituto.acim" target="_blank">
Instagram</a>

<a href="https://www.facebook.com/profile.php?id=61564453792982" target="_blank">
Facebook
</a>

<a href="https://www.linkedin.com/in/instituto-acim-de-responsabilidade-social-64237b1b6/" target="_blank">
LinkedIn
</a>

</div>

</div>

</section>

<footer>

<p>© <?=date('Y')?> Instituto ACIM - Todos os direitos reservados</p>

</footer>

<script>

/* CARROSSEL */

let slides=document.querySelectorAll('.slide');
let index=0;

function nextSlide(){

slides[index].classList.remove('active');

index=(index+1)%slides.length;

slides[index].classList.add('active');

}

setInterval(nextSlide,4000);

</script>

</body>
</html>