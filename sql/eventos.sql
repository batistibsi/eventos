--
-- PostgreSQL database dump
--

-- Dumped from database version 12.22 (Ubuntu 12.22-0ubuntu0.20.04.4)
-- Dumped by pg_dump version 12.22 (Ubuntu 12.22-0ubuntu0.20.04.4)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: eventos_config; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eventos_config (
    id_config integer NOT NULL,
    nome_empresa character varying,
    cnpj_empresa character varying,
    email character varying,
    endereco character varying,
    dpo character varying,
    email_dpo character varying,
    smtp character varying,
    porta character varying,
    senha character varying
);


ALTER TABLE public.eventos_config OWNER TO postgres;

--
-- Name: eventos_evento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eventos_evento (
    id_evento bigint NOT NULL,
    titulo text NOT NULL,
    data_hora timestamp without time zone NOT NULL,
    ativo boolean DEFAULT true NOT NULL,
    limite_vagas integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.eventos_evento OWNER TO postgres;

--
-- Name: eventos_evento_id_evento_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.eventos_evento_id_evento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.eventos_evento_id_evento_seq OWNER TO postgres;

--
-- Name: eventos_evento_id_evento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.eventos_evento_id_evento_seq OWNED BY public.eventos_evento.id_evento;


--
-- Name: eventos_inscricao; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eventos_inscricao (
    id_inscricao bigint NOT NULL,
    id_evento bigint NOT NULL,
    nome text NOT NULL,
    email text NOT NULL,
    status character varying NOT NULL,
    token_confirmacao text NOT NULL,
    token_expira_em timestamp without time zone NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    confirmado_em timestamp without time zone
);


ALTER TABLE public.eventos_inscricao OWNER TO postgres;

--
-- Name: eventos_inscricao_id_inscricao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.eventos_inscricao_id_inscricao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.eventos_inscricao_id_inscricao_seq OWNER TO postgres;

--
-- Name: eventos_inscricao_id_inscricao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.eventos_inscricao_id_inscricao_seq OWNED BY public.eventos_inscricao.id_inscricao;


--
-- Name: eventos_login; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eventos_login (
    id integer NOT NULL,
    id_usuario integer NOT NULL,
    data_hora timestamp without time zone DEFAULT now()
);


ALTER TABLE public.eventos_login OWNER TO postgres;

--
-- Name: eventos_login_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.eventos_login_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.eventos_login_id_seq OWNER TO postgres;

--
-- Name: eventos_login_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.eventos_login_id_seq OWNED BY public.eventos_login.id;


--
-- Name: eventos_perfil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eventos_perfil (
    id_perfil integer NOT NULL,
    descricao character varying(255)
);


ALTER TABLE public.eventos_perfil OWNER TO postgres;

--
-- Name: eventos_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eventos_usuario (
    id_usuario integer NOT NULL,
    nome character varying(255) NOT NULL,
    email character varying(255),
    senha character(32) NOT NULL,
    ativo boolean DEFAULT true,
    id_perfil integer
);


ALTER TABLE public.eventos_usuario OWNER TO postgres;

--
-- Name: eventos_usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.eventos_usuario_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.eventos_usuario_id_usuario_seq OWNER TO postgres;

--
-- Name: eventos_usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.eventos_usuario_id_usuario_seq OWNED BY public.eventos_usuario.id_usuario;


--
-- Name: eventos_evento id_evento; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_evento ALTER COLUMN id_evento SET DEFAULT nextval('public.eventos_evento_id_evento_seq'::regclass);


--
-- Name: eventos_inscricao id_inscricao; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_inscricao ALTER COLUMN id_inscricao SET DEFAULT nextval('public.eventos_inscricao_id_inscricao_seq'::regclass);


--
-- Name: eventos_login id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_login ALTER COLUMN id SET DEFAULT nextval('public.eventos_login_id_seq'::regclass);


--
-- Name: eventos_usuario id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.eventos_usuario_id_usuario_seq'::regclass);


--
-- Data for Name: eventos_config; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos_config (id_config, nome_empresa, cnpj_empresa, email, endereco, dpo, email_dpo, smtp, porta, senha) FROM stdin;
1	Paraquedas Consultoria ESG	35.048.928/0001-20	bemfeitosistemas@gmail.com	R. Santos Dumont, 50 - Zona 03, Maringá - PR, 87050-100	Henrique Nascimento	henriquen.paraquedas@gmail.com	smtp.gmail.com	587	cetyzjgnbhohwnjz
\.


--
-- Data for Name: eventos_evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos_evento (id_evento, titulo, data_hora, ativo, limite_vagas, created_at) FROM stdin;
1	Evento 1	2026-04-01 16:00:00	t	30	2026-03-12 07:57:55.611763
2	Evento 2	2026-05-01 17:00:00	t	10	2026-03-12 07:57:55.611763
3	Evento 3	2026-06-01 12:00:00	t	5	2026-03-12 07:57:55.611763
\.


--
-- Data for Name: eventos_inscricao; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos_inscricao (id_inscricao, id_evento, nome, email, status, token_confirmacao, token_expira_em, created_at, confirmado_em) FROM stdin;
16	2	Leandro	batisti23@gmail.com	CRIADO	1831f56776eebc81a9de8395457bac5169a09e7a99b50b5c	2026-03-13 09:16:03	2026-03-12 09:16:03.953129	\N
\.


--
-- Data for Name: eventos_login; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos_login (id, id_usuario, data_hora) FROM stdin;
1	1	2026-03-12 13:56:59.924575
2	2	2026-03-12 14:14:56.758888
3	2	2026-03-12 14:19:50.012487
4	2	2026-03-12 14:20:30.806243
\.


--
-- Data for Name: eventos_perfil; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos_perfil (id_perfil, descricao) FROM stdin;
1	Adminstrador
\.


--
-- Data for Name: eventos_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos_usuario (id_usuario, nome, email, senha, ativo, id_perfil) FROM stdin;
1	Administrador Bem Feito	batisti_bsi@hotmail.com	81dc9bdb52d04dc20036dbd8313ed055	t	1
2	teste	batisti23@gmail.com	81dc9bdb52d04dc20036dbd8313ed055	t	1
\.


--
-- Name: eventos_evento_id_evento_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.eventos_evento_id_evento_seq', 3, true);


--
-- Name: eventos_inscricao_id_inscricao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.eventos_inscricao_id_inscricao_seq', 16, true);


--
-- Name: eventos_login_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.eventos_login_id_seq', 4, true);


--
-- Name: eventos_usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.eventos_usuario_id_usuario_seq', 2, true);


--
-- Name: eventos_config eventos_config_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_config
    ADD CONSTRAINT eventos_config_pkey PRIMARY KEY (id_config);


--
-- Name: eventos_evento eventos_evento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_evento
    ADD CONSTRAINT eventos_evento_pkey PRIMARY KEY (id_evento);


--
-- Name: eventos_inscricao eventos_inscricao_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_inscricao
    ADD CONSTRAINT eventos_inscricao_pkey PRIMARY KEY (id_inscricao);


--
-- Name: eventos_login eventos_login_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_login
    ADD CONSTRAINT eventos_login_pkey PRIMARY KEY (id);


--
-- Name: eventos_perfil eventos_perfil_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_perfil
    ADD CONSTRAINT eventos_perfil_pkey PRIMARY KEY (id_perfil);


--
-- Name: eventos_usuario eventos_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_usuario
    ADD CONSTRAINT eventos_usuario_pkey PRIMARY KEY (id_usuario);


--
-- Name: eventos_inscricao uq_token_confirmacao; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_inscricao
    ADD CONSTRAINT uq_token_confirmacao UNIQUE (token_confirmacao);


--
-- Name: eventos_inscricao eventos_inscricao_id_evento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_inscricao
    ADD CONSTRAINT eventos_inscricao_id_evento_fkey FOREIGN KEY (id_evento) REFERENCES public.eventos_evento(id_evento) ON DELETE RESTRICT;


--
-- Name: eventos_login eventos_login_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_login
    ADD CONSTRAINT eventos_login_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.eventos_usuario(id_usuario);


--
-- Name: eventos_usuario eventos_usuario_id_perfil_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos_usuario
    ADD CONSTRAINT eventos_usuario_id_perfil_fkey FOREIGN KEY (id_perfil) REFERENCES public.eventos_perfil(id_perfil);


--
-- PostgreSQL database dump complete
--

