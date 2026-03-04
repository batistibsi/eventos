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

--
-- Name: unaccent; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;


--
-- Name: EXTENSION unaccent; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION unaccent IS 'text search dictionary that removes accents';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: ouvidoria_config; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_config (
    nome_empresa character varying NOT NULL,
    logo character varying NOT NULL
);


ALTER TABLE public.ouvidoria_config OWNER TO postgres;

--
-- Name: ouvidoria_dica; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_dica (
    id_dica integer NOT NULL,
    nome character varying NOT NULL,
    texto character varying NOT NULL,
    ativo boolean DEFAULT true
);


ALTER TABLE public.ouvidoria_dica OWNER TO postgres;

--
-- Name: ouvidoria_dica_id_dica_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_dica_id_dica_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_dica_id_dica_seq OWNER TO postgres;

--
-- Name: ouvidoria_dica_id_dica_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_dica_id_dica_seq OWNED BY public.ouvidoria_dica.id_dica;


--
-- Name: ouvidoria_emissor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_emissor (
    id_emissor integer NOT NULL,
    nome character varying,
    id_medida integer NOT NULL,
    id_tempo integer NOT NULL,
    emissao numeric,
    descricao character varying,
    ativo boolean DEFAULT true
);


ALTER TABLE public.ouvidoria_emissor OWNER TO postgres;

--
-- Name: ouvidoria_emissor_id_emissor_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_emissor_id_emissor_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_emissor_id_emissor_seq OWNER TO postgres;

--
-- Name: ouvidoria_emissor_id_emissor_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_emissor_id_emissor_seq OWNED BY public.ouvidoria_emissor.id_emissor;


--
-- Name: ouvidoria_entidade; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_entidade (
    id_entidade integer NOT NULL,
    id_criador integer NOT NULL,
    id_dono integer NOT NULL,
    id_alterador integer,
    tipo character varying(50),
    descricao text,
    data_criacao timestamp without time zone DEFAULT now(),
    data_modificacao timestamp without time zone,
    deleted boolean DEFAULT false,
    tag character varying(200),
    label character varying
);


ALTER TABLE public.ouvidoria_entidade OWNER TO postgres;

--
-- Name: ouvidoria_entidade_id_entidade_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_entidade_id_entidade_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_entidade_id_entidade_seq OWNER TO postgres;

--
-- Name: ouvidoria_entidade_id_entidade_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_entidade_id_entidade_seq OWNED BY public.ouvidoria_entidade.id_entidade;


--
-- Name: ouvidoria_entidade_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_entidade_link (
    id_link integer NOT NULL,
    id_entidade integer NOT NULL,
    id_criador integer NOT NULL,
    id_alterador integer,
    descricao text,
    data_criacao timestamp without time zone DEFAULT now(),
    data_modificacao timestamp without time zone,
    deleted boolean DEFAULT false,
    label character varying(200) NOT NULL,
    url character varying NOT NULL
);


ALTER TABLE public.ouvidoria_entidade_link OWNER TO postgres;

--
-- Name: ouvidoria_entidade_link_id_link_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_entidade_link_id_link_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_entidade_link_id_link_seq OWNER TO postgres;

--
-- Name: ouvidoria_entidade_link_id_link_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_entidade_link_id_link_seq OWNED BY public.ouvidoria_entidade_link.id_link;


--
-- Name: ouvidoria_entidade_perfil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_entidade_perfil (
    id_perfil integer NOT NULL,
    tipo character varying(50) NOT NULL,
    operacao character varying(6) NOT NULL,
    particular boolean DEFAULT false
);


ALTER TABLE public.ouvidoria_entidade_perfil OWNER TO postgres;

--
-- Name: ouvidoria_evento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_evento (
    id_evento integer NOT NULL,
    hora_inicio character varying,
    hora_fim character varying,
    logo_marca character varying,
    id_indicador_arvores integer
);


ALTER TABLE public.ouvidoria_evento OWNER TO postgres;

--
-- Name: ouvidoria_evento_emissor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_evento_emissor (
    id_evento integer NOT NULL,
    id_emissor integer NOT NULL,
    quantidade integer NOT NULL
);


ALTER TABLE public.ouvidoria_evento_emissor OWNER TO postgres;

--
-- Name: ouvidoria_historico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_historico (
    id integer NOT NULL,
    campo character varying,
    valor_antigo character varying,
    valor_novo character varying,
    id_alterador_novo integer,
    id_alterador_antigo integer,
    data timestamp without time zone DEFAULT now(),
    id_entidade integer NOT NULL
);


ALTER TABLE public.ouvidoria_historico OWNER TO postgres;

--
-- Name: ouvidoria_historico_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_historico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_historico_id_seq OWNER TO postgres;

--
-- Name: ouvidoria_historico_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_historico_id_seq OWNED BY public.ouvidoria_historico.id;


--
-- Name: ouvidoria_login; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_login (
    id integer NOT NULL,
    id_usuario integer NOT NULL,
    data_hora timestamp without time zone DEFAULT now()
);


ALTER TABLE public.ouvidoria_login OWNER TO postgres;

--
-- Name: ouvidoria_login_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_login_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_login_id_seq OWNER TO postgres;

--
-- Name: ouvidoria_login_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_login_id_seq OWNED BY public.ouvidoria_login.id;


--
-- Name: ouvidoria_medida; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_medida (
    id_medida integer NOT NULL,
    nome character varying,
    ativo boolean DEFAULT true
);


ALTER TABLE public.ouvidoria_medida OWNER TO postgres;

--
-- Name: ouvidoria_medida_id_medida_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_medida_id_medida_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_medida_id_medida_seq OWNER TO postgres;

--
-- Name: ouvidoria_medida_id_medida_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_medida_id_medida_seq OWNED BY public.ouvidoria_medida.id_medida;


--
-- Name: ouvidoria_objetivo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_objetivo (
    id_objetivo integer NOT NULL,
    nome character varying NOT NULL,
    descricao character varying,
    id_objetivo_grupo integer
);


ALTER TABLE public.ouvidoria_objetivo OWNER TO postgres;

--
-- Name: ouvidoria_objetivo_grupo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_objetivo_grupo (
    id_objetivo_grupo integer NOT NULL,
    nome character varying,
    descricao character varying
);


ALTER TABLE public.ouvidoria_objetivo_grupo OWNER TO postgres;

--
-- Name: ouvidoria_perfil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_perfil (
    id_perfil integer NOT NULL,
    descricao character varying(255)
);


ALTER TABLE public.ouvidoria_perfil OWNER TO postgres;

--
-- Name: ouvidoria_perfil_id_perfil_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_perfil_id_perfil_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_perfil_id_perfil_seq OWNER TO postgres;

--
-- Name: ouvidoria_perfil_id_perfil_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_perfil_id_perfil_seq OWNED BY public.ouvidoria_perfil.id_perfil;


--
-- Name: ouvidoria_projeto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_projeto (
    id integer NOT NULL,
    nome character varying,
    inicio date,
    fim date,
    id_tipo_projeto integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.ouvidoria_projeto OWNER TO postgres;

--
-- Name: ouvidoria_projeto_indicador; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_projeto_indicador (
    id integer NOT NULL,
    id_projeto integer NOT NULL,
    numero_indicador integer NOT NULL,
    nome character varying NOT NULL,
    id_tipo_indicador integer NOT NULL,
    valor character varying,
    previsto character varying,
    vermelho integer DEFAULT 20,
    amarelo integer DEFAULT 50
);


ALTER TABLE public.ouvidoria_projeto_indicador OWNER TO postgres;

--
-- Name: ouvidoria_projeto_objetivo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_projeto_objetivo (
    id_projeto integer NOT NULL,
    id_objetivo integer NOT NULL
);


ALTER TABLE public.ouvidoria_projeto_objetivo OWNER TO postgres;

--
-- Name: ouvidoria_projeto_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_projeto_usuario (
    id_projeto integer NOT NULL,
    id_usuario integer NOT NULL
);


ALTER TABLE public.ouvidoria_projeto_usuario OWNER TO postgres;

--
-- Name: ouvidoria_tempo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_tempo (
    id_tempo integer NOT NULL,
    nome character varying,
    ativo boolean DEFAULT true,
    multiplicador integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.ouvidoria_tempo OWNER TO postgres;

--
-- Name: ouvidoria_tempo_id_tempo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_tempo_id_tempo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_tempo_id_tempo_seq OWNER TO postgres;

--
-- Name: ouvidoria_tempo_id_tempo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_tempo_id_tempo_seq OWNED BY public.ouvidoria_tempo.id_tempo;


--
-- Name: ouvidoria_tipo_indicador; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_tipo_indicador (
    id_tipo_indicador integer NOT NULL,
    nome character varying
);


ALTER TABLE public.ouvidoria_tipo_indicador OWNER TO postgres;

--
-- Name: ouvidoria_tipo_projeto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_tipo_projeto (
    id_tipo_projeto integer NOT NULL,
    nome character varying
);


ALTER TABLE public.ouvidoria_tipo_projeto OWNER TO postgres;

--
-- Name: ouvidoria_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ouvidoria_usuario (
    id_usuario integer NOT NULL,
    nome character varying(255) NOT NULL,
    email character varying(255),
    senha character(32) NOT NULL,
    ativo boolean DEFAULT true,
    id_perfil integer,
    data_expiracao_login timestamp without time zone,
    codigo_vendedor character varying,
    telefone character varying,
    departamento character varying
);


ALTER TABLE public.ouvidoria_usuario OWNER TO postgres;

--
-- Name: ouvidoria_usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ouvidoria_usuario_id_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ouvidoria_usuario_id_usuario_seq OWNER TO postgres;

--
-- Name: ouvidoria_usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ouvidoria_usuario_id_usuario_seq OWNED BY public.ouvidoria_usuario.id_usuario;


--
-- Name: ouvidoria_dica id_dica; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_dica ALTER COLUMN id_dica SET DEFAULT nextval('public.ouvidoria_dica_id_dica_seq'::regclass);


--
-- Name: ouvidoria_emissor id_emissor; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_emissor ALTER COLUMN id_emissor SET DEFAULT nextval('public.ouvidoria_emissor_id_emissor_seq'::regclass);


--
-- Name: ouvidoria_entidade id_entidade; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_entidade ALTER COLUMN id_entidade SET DEFAULT nextval('public.ouvidoria_entidade_id_entidade_seq'::regclass);


--
-- Name: ouvidoria_entidade_link id_link; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_entidade_link ALTER COLUMN id_link SET DEFAULT nextval('public.ouvidoria_entidade_link_id_link_seq'::regclass);


--
-- Name: ouvidoria_historico id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_historico ALTER COLUMN id SET DEFAULT nextval('public.ouvidoria_historico_id_seq'::regclass);


--
-- Name: ouvidoria_login id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_login ALTER COLUMN id SET DEFAULT nextval('public.ouvidoria_login_id_seq'::regclass);


--
-- Name: ouvidoria_medida id_medida; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_medida ALTER COLUMN id_medida SET DEFAULT nextval('public.ouvidoria_medida_id_medida_seq'::regclass);


--
-- Name: ouvidoria_perfil id_perfil; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_perfil ALTER COLUMN id_perfil SET DEFAULT nextval('public.ouvidoria_perfil_id_perfil_seq'::regclass);


--
-- Name: ouvidoria_tempo id_tempo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_tempo ALTER COLUMN id_tempo SET DEFAULT nextval('public.ouvidoria_tempo_id_tempo_seq'::regclass);


--
-- Name: ouvidoria_usuario id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.ouvidoria_usuario_id_usuario_seq'::regclass);


--
-- Data for Name: ouvidoria_config; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_config (nome_empresa, logo) FROM stdin;
Leandro Bunick Batisti o maximo em sistemas industriasi	data:image/png;base64,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ZJlu+Tvor9rK/6WyUb4Dlm37e3v3Ne0k/cD7BGnRpnjmFP9nPMYk8iLNXr4lPer8r5RSSimlnlOX2ufNdO9lL/nWlOsgl7BhfRvNvmv699RftfZ5tT+sOdSayWzNeo3S/31tI7/zR9/8S2shrJv082soyznqR/zjMbu/lN7oepbXLK1RvybubM1pVua/iv2y3PsjX9Y88pz2wjO5zp5tJPdeOWcNl3s5JrB3sya82zrLmeuJdY/1Ztaa+rpShfc61r1MK21Xx/QZkFdeox6nxHol90mXve6lMp+j7pdsb6P+z1obtmY/vms09le83Mct6COs860JP1Yv7JdjXv+3IfchEHsZdcy1yrRVptnzGtm3/xNBnNH9kf9HZT5Hff4/xf8Zf/b+kHbinL0Zjvgz/8lYE35qvfqcl3sC+HpUp/RBt09ez/LKsNE+E/ezP3OdeY/KfK628H/fRymfUKY8LzHWMX4yltGe14afUi/CGDf4jwAb074Qc233fx9zco/ymP/5fyLzKPX73f+zMp+rY/7PuR079H6SdS318Sl9g7+Iyzy2Vfgxu2cYtuT9OudhxnDiYue0NXud+DP3KI+Vg39r8SFtJ23KntnI/6Myn/MuyH5b1il9R9/OumKP0VhF3Eyv59f92fvBmnDCluqVYdSDuaT7N+fy0TcYz/fnRnn1MNpA34tMGxM/856Vufe1S2hpvUA9vvS/UkoppZRSSimllFJKXU07EREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREREZE75B+Hl45q2TuOnAAAAvVta0JU+s7K/gB/o2kAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHic7dzNcdpAFABgleASKMElUAIl+JSzS6AErrm5BEqgBEpwCe6AsJNlbGMDkpD0dtH3zbxbhuz7YXclO2kaxvTS/P1zKDii6zMHmwL6rP+x3grotf7HKnEGomsyN6XNQHQ95qikGYiuxVyVMgPRdZizEmYgugZzFz0D0fkTOwPRufNf1HvC6Lz5FDED0Tnz3dQzEJ0vP005A9G58rupZiA6Ty6bYgaic+S6sWcgOj9uG3MGonOjnbFmIDov2htjBqJzopuhZyA6H7obcgaic6GfoWYgOg/6G2IGonPgPvfOQPT6ud89/74geu2lWRxr8lxhXfr+Dkn0ukuRer7/Upf3Y6wqq0+fGYhecyneL9TnraIaPZ3NsP63s7xRo9pm4NIs6//vnlvUqaYZaJOP/n/X5juzO8ZTJTVb638nq5b12lf0fNDmLhC9xpK0/c58NHU8G9y61+j/T12eod4qOA92+t/Za4cZSPeGZcF1vPV+OHp9pVrlfb7tHGyPsSiwnk/639ui6fY+Jc3LusAz4VoO0WurQdt7YalzcO0OEL22Wpz/fKCmOZjy+7/I96Ep4jXX91IsRziT1x3vBV+fFSLeGyx6rPXRYuj3dqmm255rec9zO9Vdse86Hy12I9R7mT+375rSvpz2kzH2hTTv0f+3SGkxdI1P7p2DFB/5M+49txb5M/qcUY8cHyP2/yT1bej9dpdj01y+52zyn+nyc9+5xWaC/p+cvoM19GP34JH6vpyw9+fSe8R0Dpe6H0fVZY5WeR5L2heiazJX6Yx4aT7Pbv0nPQemPWKdz4yh73T7/Jnb/He86H9V0p7R9R1p9JoBAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACgSIfDQQghhBBCCCGEEEI8bvwDpcSTRdUD8qgAAAFTbWtCVPrOyv4Af6WFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB4nO3W4WmDYBSGUQdxEQdxEBdxEAdxEQexvIELt6Yh/4oJ54FDm0/7601szlOSJEmSJEmSJEmSJEmSJEmSJEkf0XEc577vT+c5y7V397+6T/dvXddzHMdzmqbHz+wY/Sz31L11FsuyPF7HMAx/vod077JjlX2zYXatzfs9tX/VN7/+je5ftut7Vjnrn+V6nX37xtm/ul7T/ctzvu9f/9fneX7aP9fs/31l23ru1+/btv36zPfnv/2/r/oe1/er90Cu1Xf7nEXVnx3Xa5IkSZIkSZIkSfr3BgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA+EA/CvmsuFLaKmYAAAMRbWtCVPrOyv4Af6agAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB4nO3cwXHaUBQFUEqgBEqgBJdACayyTgkuwdvsKMElpASX4BLogPhNxAzj2DHmS+j9p3Nm7i4z4XGF9L8EPq1+/TglyuNbVg3WCWa4Zx4a36+5X//7HN+ybpzpNcEc+r89reeApwQz6P/2HBtn2iWYQf9t2TfMtaQ1QNX+fzfO9ZxgBv23ZdMw2z7B69d/W342zLaUa0Dl/l0Dlt3/qXG2JewDqvffOl/1e0HV+29ZA4THBDPo//aM8TzgmGAO/d+W58b5wiHBHPq/La17gLBJMIf+5+s/VH0mpP/rVF0H6P96FfcC1fs/jNh/qHY/oHr/rfu/9x4SzKT/67Mbuf9QaS1Yvf+WZ8CfibVgletA5f5fJ+j+rMp1oHL/Y6/93quwH6jcf+ts1/idYE79/5spz/2Xel8LVO2/9bnvd2xX/d4bbN0fz/36P8oYvwH6rm2CufX/N/f87F/q8TvD1fp/man7s96OgWr9b2fuP/R0DFTqf+x7/S16OQaq9D/mc96xxN4q+76gQv+x/773ev9a21Xu+wO99x+frwzX/P+JY/MlQdfV+u+h+0sZnxv32n9v3Z/tVrnWBD32H+fSrNf7a8R3ErI8N+qt/0Pn3V+K+5Rznwt66T/epym+yzW3OBfM+TvzHvp/LvSZ/0zcK5hjn5i5/7jO3+M7HJns73wcZOw/5m/5+129Ww+93GNtkKn/l4X3/pGpzwcZ+o81/dLO898Va98p1omvw/v/OHz2Hr7Ifvi3kZY9bHzWY/9TfV03ts3w3md+pvBV51P8JmOJ4v7nU+Jj4Xxu2et8ctsRzsktOQ7/d7yGnb5ntR46OB8PU+4jYk3gGp7f5uKYOAzHxRjXjbnnYhzb1ddr/3N83gEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFi80+kkIiIiIiIiIiJ18wcIbks5RN0yGQAAA2Fta0JU+s7K/gB/qKsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHic7d3NceJAEIZhhaAQCIEQCIEQOPlMCITAdW8KwSFsCITgEMjAZspSFavlb9BIX3fP+1T1zWWb/hppNBJ20wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEMum+fOR6tBXd6m/o/q+U+fR13X99xC+HNyxuuSy7fNJWX09yHVqqV8rfvPeXepz5qxv1cFpbftj4cbpDK8vv/fxUqeF845Y6dy2djAH6X2+b5Z/j9dQX4bzT8eoTtibdIwZ1n2RjzfqnMc2zeM1+Rx17mctnR/bJz1pDWRW8nUvFOtTqtwPL2Q+ps6tVB0N5L8S5D5k/+76Z1hDD7Vv5luzH5v/9yumVrpm2omzb/vXp5p9D2vfqFLvlev5juxllO/5obbkv7i20Zznb5XX/S+v0vHe0jW0eu1Tk5T92UDm18X5fxkWs/9ubO17RGU1e84B82uNZ5/K8r0P7yyt9R4Vz/KUZ+H6PqfYByxnYyDP3DqRfzFejvvj4npwup2BHKcU1wPTRHg+i33h93h/7w/l5ZlIa6zc1yk1A7nPA9VsZSCz0nViBl62N5AXM6DzaSArZkDH+j4/MzCfSM/CMwP5PO73MgPlRLnuZwbe4+1eX6kZUPc9R5rX8edVrmvKPNeYf6pbn6lJn9MZf3anu/O1r9aS99PSz9pnzkKt+UeunHsg5B+vcp6JIv94lZP/1sDvS5WrtJe3ysi/puv/67KwXitdaR8/9953Lft/48rpkdKj677rmvIzIjz3EzX/JUS+/0f+z9W2B0z+/4r4/A/55/G87iX/6aI+A0b+r6ntOlDdb4s6A7mQv05N60B1r62q5Rig7rNVtRwD1H22rIZjgLrHlnn42z/kP6/o+wHq/noQeU9Q3VsP1gZyIn+tqOcBdV89ifS3Icg/X7oeiPaMkLqn3kRbC6j76VGk54TUvfTqaCA78teKsD+s7qFnaT3ofW9I3UPvvM+Aun8RWP//IOQ/P68zoO5bJB5nQN2zaLztDaj7FZGnGVD3KiovM6DuU2QeZuAHiqgGZ1OaqCkAAACnbWtCVPrOyv4Af6jVAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB4nO3DMQkAAAwDsPo33YnoNUggCQAAAAAAAADwXltVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVXR9x5WHKM8/C8AAAAhlta0JU+s7K/gB/qRoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHic7dpdUcNAGAXQSKgEJDCDgUpAQiUgIRIqAQlIiAAeKgEJOAC+IZkpnQLp737dPWfmvvWle7s/2bTrjvPYPbx+yKy8f2XxlSOHOq11grG9hTxW2P1kk2B8M+e54u7DfYIxzpqYGzWu+7v6BGOdLbHn3zfQfViM37f0mGfKspHuJ6sEY54lq8a6D9aA7/QNdj9p/Xmw9rP+f1p+Fmi9+8lbgi6unRb3+9+8JOhD9+W0chfQ0vP9IVroP+717nS/V+39r/X+p1r7j3Nta3d6x6jxfWDM+Rbe45zqLkFX5nw5Q4LOzpE42z/p/SDPCXo7R+9xfrHWzxdjVcOdT/x+PdPNtxjnyi2/85vmu97nifuuVQXz/a2zzk/6cSyWY/qdRNe1nOuGzn39rtKdXDqxxsfzuzV+v9L9XKrzOM/V/L/7cynd1SU6t6/PV7q3UxLnuLV5fpLSHR7ad8zxOMPZz8+jdKd/ZTPOb31fTumOI7F3D1tde+dyPddcu4dx/e71nMYw9rKd3f/wbvZ8Zjvr7ued0XIrpb8fAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC34RPwWXuBpH8dsAAAAf5ta0JU+s7K/gB/qmoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHic7dEBDQJBAAPBl4AXDCABSUhDACJwAjL2ks4mNdC5Lr2u++c3uvr7E+K/3fMAB/5djwMc+Hfx347/dvy3478d/+34b8d/O/7b8d+O/3b8t+O/HX/VDvzbagf+bbUD/7bagX9b7cC/rXbg31Y78G+rHfi31Q7822oH/m21A/+22oF/W+3Av6124N9WO/Bvex9gwb+L/3b8t+O/Hf/t+G/Hfzv+2/Hfjv92/Lfjvx3/7fhvx387/tvx347/dvy3478d/+34b8d/O/7b8d+O/3b8t+O/Hf/t+G/Hf7vvARb8u2oH/m21A/+22oF/W+3Av6124N9WO/Bvqx34t9UO/NtqB/5ttQP/ttqBf1vtwL+tduDfVjvwb6sd+LfVDvzbagf+bbUD/7bagX9b7cC/rXbg31Y78G+rHfi31Q7822oH/m21A/+22oF/1+0AB/5djwMc+Hfx347/dvy3478d/+34b8d/O/7b8d+O/3b8t+O/Hf/t+G/Hfzv+2/Hfjv92/Lfjvx3/7fhvx387/tvx347/ds8DHPh3vQ5w4N/Ffzv+2/Hfjv92/Lfjv92y/x9JWgOdPuV/2AAAAMVta0JU+s7K/gB/tlAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHic7dEBCQAwDMCw+Te9izicQ1OIgs48aHf5lP9t/rf53+Z/m/9t/rf53+Z/m/9t/rf53+Z/m/9t/rf5DwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABcOvRkn5fieJFaAAAA821rQlT6zsr+AH+3BAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAeJzt0QEJgEAABMGPJFjAaEYwkgHspLb4h52BK3A7BufYnze62d+vQP82/dv0b9O/Tf82/dv0b9O/Tf82/dv0b9O/Tf82/dv0b9O/Tf82/duuBTroP8+9QAf959G/Tf82/dv0b9O/Tf+27f/hiG729wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA0fRkKgFb1NJQ2AAAA/m1rQlT6zsr+AH+42AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAeJzt0QENhEAQBMHzhKGXggQkIeA9AS5uk65KxsD0WgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAATPFbx/+Mbvf3E9zfD090u7+fQP82/dv0b9O/Tf82/dv0b9O/Tf82/dv0b9O/Tf82/dv0b9O/Tf82/duuAR303+cc0EH/ffRv079N/zb92/Rv079N/zb92/Rv07+t3P8FPq8EQhjPPjMAAAPrbWtCVPrOyv4Af7tVAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB4nO2dzXHiQBBGCYEQCIEQCIEQOPlMCITAdW8OgRAcAiEQgjPwqqs0uy4VxiDNz9c971X1xYu9g96n0WhmEKsVAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAPMNp9edtN9R2qNZtgd/ZjL6OQ5m796E+vtVtqK879Tl53cf4+9PX2e9fxn+z/2dNLpqxHT0nx/e81qjrUOeh9mShKMl3S9fPlPUPB/qGLOzH8/unflu9rO07cvAS27E//RTwl6ssv0f6hB9Zj33mtcCxn47tLFunSU3HiSl7u7Fdp8nPl7TlRA7+sSl8rp8KHOc0DrlkyEHutnlhM55zpfvdGsc4jVHmZPi26mt8UMt7Tf+JdA2bc39ifWDka8J6dFF7TNeqj93OyLmNfTYBM7Bv4L21/8Sr/Z0dpyhzzZuZfWEk/4lXjkWEDBwbnvOK/hPP9oX2Go/XgrXAOa/s37Bj9My947tg2x+xEznn1f0n7q0vTqt1G5/lKODam3/j8OCcuYm3PVHzfj6af+Pe2NAyob6ebNexEvP1vflP7Fb/1yPUx34e3Hvz7wUv7vGfH0/u8Z8Xb+7xnw+P7vGfD6U5PfzXRfn+Hv9lOQg4xH8btgL+8N8GG+953XOP/+Us2duqVPh/HdW1vDmlvoaihq07qK3hL6me9lbnwOt9Pv6XsxfwpeY/8p7870QZ7+f2b3PePWTgmb1oHmupf/sb0TNg7y3SmC+3/5QB9T05c/E8v1/L/9cqxucypmwEHHnxHzEDUa/7pfxHykDk635J/1Ey4H1tt6X/CBnwuJ9Lyb/nDERY21fw7zUDZwE3Ufx7zEDEud6W/j1loJe+v7Z/LxmItL9Dzb+HDERb41fzr56B1k568K+agZ6u/a39K2agp2u/gn+1DPRy36/kXykDPY39lPyrZCD6ep+y/5SBlnvJWvvo3b9Vq/2EvY39Vf23ysBOwAf+22UA/69Tun3Xiv4jfr7Hu3+rWs95jr7X814t7V9rtbNGBnr0v/SY1Wxr6WcV4P937B7Jnn3Rap7kUDAD+H+MyrMPSn1mHf+PUVkbKzVP3OP9n0f/VrY/M/fcAP4fo9L/p/rAf1X/RvoOz/Q9DK3bf86YgbXA+1H3P6V1+61yPsOs9XvB/+uVc824h8/8RfNvdcnkP8pzPXvzb5VjXkBhTIP/eZXj+wB7uwdYOo/yF8NIjaJ5lOTYAAACsm1rQlT6zsr+AH/PTwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAeJzt2m2RIjEQANCRsBKQQNUZGAkrAQkrAQlIQAISEHA/VgIScHBHaoc67m4H5iMhJPNeVX4Ck3TTSQeaBgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACgbKvmx8925gjvkXsefG97iU0Yh8s4duNXwnG++Zxd99mbLk/e5MnTpYz11BFyY38ZH/IiudyxHjpONzmxlg/R5I7rnH3k0OWD88V0ueMYsz6E88S7XBgld9xS1Ya9XBgkd6yekQuhLjgzfC93fJ45PpuvXlM/8UfumOSqCWF/cG6cF//wfbqeudqBa3l7n7jtXh/6/VOmXDiMePYajV2vEKeUPVfbvf/hyTlxXGgejIn7JsP6rLrP3Xd1O3UeLG1fGLomr3JmCuf4sG98Js6D7QvNOaVH6/Dxwmuw6p4v1T5xWsCe8Oh7n/v5hgp1YZdoj9hVXAvu5X7uZ5sqnBdi/44d9psa75D65pvjrBdb29WwWDlwrmRdbvXNtaZ6t4qcByXti4/0zTH3c6UQMw9eqSeao2+vy/1cKa0jnQ9qWKclff//9d7M7xdK3wuWsP/fE+Z5mJkD24LXqm9OS/vvxNxaUOqdca11bYq3Zvq98q7Q9eqbz7nQ+cQwpUc4Frpe9+ZU213HGBvxL/oOOIZ2xJmgtvqvBnwZ0h+EM0Op/dKj+C+9Bly1zfd3RqX/Nlh7fxvbuquJoV8stee7NST+50rmyv9qP99y35gexz5Qn7H3HLX/H25pxsb/XPh5l7+Njf+138393MQxJf5hLPH3oRpNjb+7wTr8BjNy64tq+Zw7AAAqF21rQlT6zsr+AH/U8AAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAeJztfSu47CjW9pJILBKJxCKRSCQWGYnEIiORWCQSGYmNjIyMjSyZf1H7nO6enun51P/UiFpz6T5Ve9cJsC7vuy5U5ud9Np4A2rORLcbXRmCHo8MCcF3FBWsA34V+w/NiHeCBChABtgob0J4kwXfdk9mxhMHxzXxz/PFDbwCVm91BK9VxM7a+bE8VFOB4nUDhKOkw8GG5rys/9wkSoNxULKRfjQOvN4G4c1yd297PmF5CbDEp/EMR85XSDw8r1GvbKO5GeU4iOPWWZFBDzF85FsjSPk8GMCZsqzS4j0ltK/7u626Qd+7bRxePcsWw+I2Z4AE8UaqDcQkP0RQJK6+XsoVIk30M+qGuwWWhtx1/cY+5rn5+glspLqM1Y4OJNizW+rjFwMGCbQ6eHQR1T6D476g5cFz40/08LxsPLz+8/Le4TsQ6Ep6TTcKbBXApthUgFfbEnC0c1R4ycMAnD4d1S3FgAr60zV+34NrmwB/VL7iZ+zb8NB08fgCFC7QeNzdT6huBx+FO3dVCUdfh1u3z66eolHVN4Pd7j477NcglLkKmTsmKCxhrOhgJa5tOwLxtgTnYD/znAiqndYFVxXwyqIbZJTvR7xGBm6sduF1G4WHXkinPC6pSRSVIV2MwTWcDxj67+CkvdGlok2aY9dUJ0bhYhj7KyhyoEstFy8Xy4ykVltQ8DyzpNvZzNMXhwH/WNZt64GLwP6SiSh/w4PZcGzwZTxxNJU8jKDfkNuA6pxY9CZ2q6v3TiwdZQaP3woNIDbarCJBMoHM2m84DTYNY6sj5QmNYnSbHmEq9E3QEZbsuyvYS/KjPCTMuGGplKJTPP9Q8P50tMekkcJ1PAT0A/X94FBoSjAv/2v0JH108SnTCobdWZ5uaYHxJLDzkuJV94EbzDlFqXdBvJVtQYdH9AYg2/RhYElm/zTzhF6o/EKhZb2gAgEaeF/wwNjfhga0fNkpG8ZdHW/CFBXd2KZrPNz8sHORAd44KjQZuTeDHpt0TbcwFyms+P/XoyUzaau8PsxU9gN0P2iV3g1qIaXpGVHgGgRD0hCQRga9rUPY4m0W3kG3y+AlqQU+Z8dTX+t6Aq54cPn7+kobl3ODYhpG6BulCOfq14gmGC9akAjhVratLHA5Dw3a0amLrD0wL6OXnQ7wC74B5rwWhC+cejTukbRdqC1Au1AUgl/jj70Rr8RXC6nf+oVX/RcLCgDP03KjBlJGVkjh461XAhUrK/LlzEo+jEomeXISzCA7oyZ+OKzsGfQcEc60YRhDjHVEoHktJre73pljdm4TGqAq5MQvL+v4rS4/6qOhkWIwfXTtKxKOO72MIiHgknadE0de33g8QnqITWGBp1x4g7Kjr0RBAbMyP+3JusG0kgajGXtc5zoTvekJHz56gUT0Vxm5mEORrhETq9qxlOwo8qP34FmHT/D4steKinptqxu9rhzBCn1twKPXiJL8dALqHx6CR2/bMcP00DG7LGctxYJRYxpP5Cfp2z7X26BjZLnj1SG6M+41vcp9KvoDPNazxweD/SOAcdamJ8errh5ePC2bgpxYM7dfXYewYlYaJW1oXGTo+PMdNQEqjOfMC/QKs4iTTcV0VAaEAfT1IhRYMawTQ/jPGyhi646/56bK6dL9Rkz1/ggEsCTfGxwa137v97Orncw3EPpDjojP4tu/e3DZbptFnlaiXDFJMjdiNqqj5Ea0/F7coDI0md90uN0MjfkJ7CIJdr9MK1+KXVdRXArIMN5nSMX9qa36CZZRjR7u/chbLx/kf0ONE2C4bEj30y0u03O7rCMVA3Vfdx7FNEgP7MOWAkAPj++3o5LwwzlwG2vJ4f5DzrnbPcd9OWqILPiMExg2DhIzgQkWXCZmlKZWCuiZ52EF9dOU/QvvqC1nsbSjCV0lw4YHJsfKA8Qu4fL0ylyvo/eBcMrf2IO3eKZBs3Di31nRsGAUcwUBaLkK9gKPvGASVZfGFi42DUlPf9IHGg20+ZJhJgen+bP708idODWGGZMSiRzO5JY2GvCOrKT/ovM8kBQFzHxzfCQNfNT0Tsu1ZHMdCUiMtayJxR1At0GUS/iLnZq3BCMLhJdapLc+TMx436tDkzMg41E05mRmBz4oZiiwbrOjkXypuO0iCwfrGRRZCxrGGHdZjbL9++M7usecNy51bg44vc2GfZ7hJFRdFCDLlLHoD0jHaF3SBGzqSa0zG0+fOwQahze0cyJkID+Wji0cp5hzUexI3ym/wy8VuZKj4pOi38OGVe0By9VCYPhDGa8J3jGvXvb6hCyO4D2tYF2Z5kRLWRcf3mshBqc1CwjnCdU0QsNveNFA9uV8E02ySkMZnV4+u2IfdTpUU1SOWX26Zh0fvpHADcAssWoUeEv9VdZs2yJP3w1amm9OwuOUwRUuRNyp8t/0YXa97nfw3NUZc6dS2u/p6UdgVoHoh4YLHBwl1FUiAPu7/86Z1cJqy2vb1VNmju28zUCmI+LRb4F7VNuPW2vPjYCAtmmQmEuEqPbYlxMDKZlmSPL9ekoPYt2BfNp2o38h8aB24zOsFM9ihPoCEMiAZULoQ+nH/1zcHFc+Oswv91Q78LE5zvmq7Rpk9QrWK/GALqO2Bs5VDp/L2BGmOVZIpAVLpkI9ATMXfBtKuhIv/iR0Ct8enbWI8MhNGSJNScbCyHMO5Rr0e5eP491gcummN5I6y9U9trEdB/d0Qt/TSfTq2Khq+yxN1DMRmBdg6HUDKq1JImS4D8tnvirA2wvG8scM2jmqQ5QGnY+ZHT3BPLQ0Q+q02HUgX0v363Mp/S53JSubbVcDO7BY6ukrHg76div3Jdjxneo7jjOgE8SDx/wgxRipxbZktO5MNSfKNFAA3DT8D3h7iT+woWXIN2WRlxwrPyUYGyhcN5ZkJ0vrRpf+WcsXYSJYQH8vBYezHx9uh6KU+GMYQACyhlbivM/+LG0TsWgiLuUXxysauAdJxcfDs2DdwG4E/uIPIjN5LrAaQ98UlDsinJIE7D+K8Px79UaxyGI02s3BQAzdgvGGZhrjpXj2EB4T9yVLntl8XhvWZsylto4THPsBEMyMewqMMvF8nDedJ/sIdya11D82LQ8HKLVKNbhBl46+Es7LP8x9zc5XA7kzPzDzIrS8TteAbUil8THRfMbvp8sE8dfV9RQpEyHpswvEkFEjTEQ4r704IHV8VMuy/cwdjAduvLfJySJFWLqAZs6WI9Br/ztTWjyeAke+MmYUofQvgbwmy7Tpd6Kyn2zanRmhsd7GGvECM0nrGeza6UF+ZPwwBtg1F6xvS3RjQaLOi9t+5o4PDdqLmS6sML/tC6SJN0v6yaDvA1/Hx+hfnBNCxoW+/6ylnUgJtIMMkrDW/LCCURYN4/Cg/qjoTtmfAVeu1hRdGvDSemerAIAno4BYI87XfucNFNIyBBiGWs3E/EGzkmAeQ9UGu9Q6InxZZdrTuczptUh6qKEcH/7Ba33naR3GEK3cwESlOevv25+F1iFn0LcUmlaeP6MAiolkQCT0nSYb9zh2DOPC36Bh7u5ltiBtML36EuY8Zg8Ih/o/H+/8u40LvruDY0cxBPaie+Oe8sVmZywx8egT08DpmiRsjwqx/b2i5MlhqgfjHvEl8MdbYaTMTQSh8+ad2EGYxxQMTpdYNTkuAiJpMwM2rGtoun+vT6z/Sctldw3FCU6BeI28W8v4ubIAlBHoC4uKBiw2vxPdZ0uN+aYjklINQrgCIcRAe63UmNyiEBRz5VTtCAqGSbCB6Rut4144Gs4Gii02b98vyCyx8UGYMVvXWoPZrgpEnm0669GLMlC+hJEVOlbmqCkgDQddp3vtRCz2CdS0fL1TmUUFEOZOjqNJn1exX7fDgJVla765cgJ/aYdSlpOM1kE+tanKoD8vR8an4dSI549ZC2Hpwg8ys1nZspa1sPQuDEI8eFcm4Wezox3mfFdy+NXQD/YWm0hEL121Fg4F6niv8qh3vTRuxvos+qEy/a8c9i3JyDDSNA/ns6qf8FC9n/Q+aRcByEv7AflCGGKZuQt9boK5cZ1sVe6Grh5JnGqPjWdsDdlKfVycbhocKe0ZlsG0x794BjHsLAt13vgcDTP/VO5AdN6gmJJHn/nj6Y9r4w9AwnwuBjp5u3faJ8+0mEfradcVANXND6BRD1bFtnPEfOEgYg+NlZvHvucZ0DJLOPFBKWv/0jrBAg4/vkPnI3P/oHaG7FjSdS3yujyNgDhd9F2GfaxFSTuL/oCeXfklVIcJr8lcBgIFMjJta1/VEmAROS5XBpQX3zKFV4wYMo5zPxPf93Tu0mmfMEu9MfmEoXeWv3iFCanboKNFm8sf1H6O/ufRct/NC5QV9kkF1SPdSoaSgEQbOAgDVZ+v3mO4aTR/uC6g8N4cMT4u3Osjtylv3bTZ17Xb2jt3HOzOO5rU9yPzudx3pp3eMbh7o+6//+PqPlwSkpDNwS/7OTaKktqWDqKt78y4AdAuuIqED8250mho/E+DrjWRp8bBizEM2s/M9sMpFCbMZoB6tHtUOhSyApRvRrk/ICrKc9TC5aP52h8tHF4+SOx49uu/1TVYlpRP295vKqohy/KcAwOTCNJ1IGA0dOHLk2dQGS+yNgMl4uu1BHPQ6yjIN2hFlwC6prAHX3Z8wTjxnnevkg/iZJ4imyu7NNqPphyXBw0fMMdbWt2197qFeaq5u7dK901P9MAxDegGLx+1MWIYz/ZzIVYP2hE07XgXi/l4VflhjsL2OgAFhARrodgNHSAV1IuHnDTGK82tO10v9VII/LIjZ53KDPe7cjoZYfTZDQhBXNtu7AJBG3xeoXO4zlm17NCFdOf/hu63X3Eo0bukU2BM1StNzhHeC3F4MqkSf92ioD4KN9Ix69oK7tqPf/Tj/leAcUOuUXZd6nRfw87oxtht4peJ+FwD8tUo4I2O+JYHPvhOut2NGe2Tzlxvd3wMdur1vHfeIQHfFMIlRc1Cv47kSml8VzIHOID8IM3lCMsSQe3y+/wU1s6e4h33LPnh7cShhv7Lb0YJhoT8FgI7Q/lGTJfKnzGzBrPY09IKkz4J4bVdJ14aAR+2vpkPoGtL07DES6hKSCNsSa9dR1v2MM2lKaBvcLMf/gPrj+okaS7qaUoj3xcTwohXEwsj2yE8BYPrI54XKsruGjzwh841bEJ64TnfZ9LZhxNz4tqJagI7AeIlcUnR2mgHSXlpK7d1hXCgByh7IWplQRZaP6//uIDGKmt6jBaFojuD3nex5BjD3UwCQTCHIeQ7NUQNQD8yeEO0jUkDTsSY0r2GfORACJzLJAZ7Ei+C2SRWsRcc4WMn4SXLVxAo0qBOWKnme/WIfz3+Ly7zTGi8jiQ14sN3R3DvGMlJ+FwCqiwH14hnW4U83z+2iaO+T1ZhVjvNeCKdrBPQNu5ql46co5L6gLKWInzIYh/zXKc9DB/c6KNmQO5ccUTM+vf404Sn6JYj51GI27hdCOAH9XKAUH7MAcLX1msnsq2U86rrtU+m5EJCC2OzaK9Nqc/DEcIyEuAjfJTwmGXR7Mz+MowisfE4GKXA3EWKZ1AJ/7uPpP9RhpGnkRBO1V2wIf5IWAaG98IhYl58CwFraPjt1+J0ppGtvAykjV+HIzVOabq5jUr149JR7W8BzWHYxpKw5NYkRX6warDBL6Rj1wRiKEbbVmTfaPp4AVHChNYeLuNm0pGwaM6VT/CLYnepM7r2IWJDqheedq1vhNW32ofgODLq/UQA9InV99pHGcM+YKniNYvbVibru45fjI2lNK7P5QLtaIZAJ/rfPrn5q4NJZlN2sFRiRobTSJB4/NYqVoG0GdOp1iF0ghyWOQI733YU6DjRoONuDuJihu3R17BczwDv6Cs6RT6QxQS9yi78EvpkFChvGEc9SKjXAx/v/y+xp3CZqIwRZHjI6uiRaCChhrWTmQN8+J3oKnhQGhNdMEKyvs6zbAhfrh7apvTZakNHAOHxgG8Y23SIC5YxYATHfX4APegUnEA3uRi2p97vRj/s/sPpYXgLyC0E6PzEIogc72MxoL0sYnlZCJ/UHDPx2T24SHxnPBEZT8oK8yQz1Bsak6rDvzN5Rez1raDeZwBdN5a/Ad1hR+XD8XHbvzZPOTy//ti7F9trxuQr0jU4zt81IS1LwyWyKS5Yim3EdD/KUHoleV9wEs2iBvDF3dPke46ALaEAHAqes0TPwZRIfNv5OfJaSF7bBqYtJO3nuj/M/HwM4dFsGg1vpIZEL+qW1JCwfzq5MrbdlliKPBXqm5SVJ3oZB6mvczBcRUuRsITN1+jjg2oF5E9/rPxNfnlfF6b0pg0FiQ9L16fVP+SFyer+EYaKkNVOxzW7Wl6OziBEjwhQ8/TQzeY/cNiKqFaDSUv3q0fTfg0OBglEE5b8mPrhbj7wjCkIASM3Hvd97dqFl4AXXa0/D11TJbHEoj1VIA/DNtWiPDwy73ZQ4ELosQHSwtfbIw9WCTNt7cAi0GZX8H4kv2CrLTCKNFGRfeQwf73+fayw07gtHzJb90WJEPizBzy5vaxIi/UQ7hnw3llsuFRy1RNZD7RdBnJ8R5COJacfm6Wz//K+Jz5+hSdas0BbyCOLz3h9Ev3G9XSveGGVFCZXyll+rLS2gmYOmC9qwY6kcm7Po54Be+L+lTPQSmHGxMX4R6xBDkN9Dk/+U+J5DkzmhjghnTo0R5PP9//sak/VIyAQ4QhZraOrnq0rBjiNapC1g+laBb6eZTcthIDlyGBEXJAAT7tW6FANaLbxo82to8h8KHz9DkyS3CftelvF0xI/3vzlkKJE4FlDdhV3atpqj13dbEqIBd2wY6c87tYxkldRul9eG9G/OS6vojWT5DEgapt6EKET6r4Wvn6FJbvxJzCBN7+P8XygA+YG8DhnwGpySGO7wNSk2Ekgv9vXMWc0xh7ggsVFS5oxrHyxuy9b7WEi9rQbKifAOkYPKyz8UPv8YmmRmkwQB5yY2s3/8/L1eRX8VSpZtixIUqul03sh7pUOXtZu9zEOsAmNgve7ZMMqFdh41HcPCeDzkg/NcOVkCt93/Y+H719DkfTHaMDYi17Qh1o/zn+s56mRsOieWDPsxSCLBPEhOtgImXQvENc/2jza2OcchFkntMTsikMke+O5ZeEHP10stl3n1f218aH8fmgxkHA2iIl3wz9f/2+u5CFW5LmFrq2diYncyNKyNpv2Yg8BqLbkgUQ6qzMIAT2SWLdYE1sE6TooUCWRHp5fLpU3Z/qXx5fj3oUkJVvhHPbNX+H8hAXI26Zt30Ugz87EYuxb70nAi8R3X24sXDAG5oYKjI2c2KnilOR/wroTva3tIkK48V5Co9gjt3EIWUd+NT+e/D01WBBH5hXtLaPWfXjzMRn8ViVcNHTzktUzAhsf9OnckfLBvWYCcLVFdPBPKq83aIeEh5Z65+/BGzx5xQBB9M2ahUvglHbuYjW8VxL8PTY6j0AZyr0T18vH+DyvLTnzsWc1Z/JmONv1qG5dyAzHRMRVrNPj6aSdYyRn8ZoNcOtxlrt689yDcfrlQOZrl0jHt342Pswr2H4YmN444UaFhcGX1x/Hvhuj2iDUgOW9zpk3aeZcJ9UsELdHbdYqkdRY55twHQmR4N0iHVpm+1tgmpl8PqK+dIUPyo2wBGGdMDiD/MDSJsX+3eVP3AqV9fP5x2bPea9Dw7AHZ+sxirnM6AWa6Jy/Q/ILADh3jvLNAIf5dJbmD3Hoj1z3ESqRzx2Azl39XIGV6PI1QSUfyD0OTgq77MKhA6DTtx/u/CwPV3h77NbgCNWe1lXj/Y47tVL9H9Nz7VRn0I69S1BtDQ8Y/dGR4xxz0hvhMYIzGgTin9evpZGdzVOI/D002fSwMAl+dmpMgH5ZcgmvZrATe+J5sdM6EbK9zoIs6bSIy1+M1t2IBZVxdCFzyDMub3OR7eGHfTG+5i1HTf2xQd0s3jezpPw9N7qWJAF5hLNUfX/5sYijUwDGHP/G/64MG7fMOzzOTHYTdjF43otv2OvAQhcveg8PDXrp1c6zPmnFCuTgqwY3oaIBHeIwfsFn+D0OTbTUCg01+7XtTH2fAOW7okVJYlh1DfVv5q4sXn2gHT850Q5uXMSNXM+gHKpr7Oju9Jl8Yh0cU29uCtCacSHyJ3dDgweg1gkyRif88NMmD7/JcYgWm+8f7v4YRl0Q/XWZNe1Y2KoJT5DyHm9nbZZmNMCygIavYDUG0y9i+vOf2heSh9oxLuAifbaScbZ3Bxt+Nw3KLnb1P929Dk62kmvy8MokKCB/3f9bhI4PDcCcktEaQy79AIdJ7MJ4XVoQRpllXqdjCb2WtLKmKJ6qLSCe6v/dg53L9Mc7i2ugVgyOazb8PTVJTlhrdEBNZuo/ff5JaQh3QaMR8lniyt0jzQA0221l6aVcfbIR3URPBDBEc4X2CeXEPF3PgreyzIWCrsx9+eSOiLU8Y3QvVkar2t6FJoliV95Bt1ssRFH+8/gfxqMx5z/GB0fWffO/8KjBvQKKBG13bk4leKGBQDxHKce2rwoN2tq1lZrcB6c927ieaT0E9QoD7HoyD3YJw5O9Dk0ojCryoEAzWnp6Pp/9xleY1sQ1S0cPuF7qA64F3VibthSkM1KmD2W5AcG/vjeeyXd3MezOsdrY6C/oOGMf6tYbew1mR6M1mKmFX79JfhyYnCkprMG6liaKvRLh46I/7fwuUXC9Ik9zMyUQM4XUDznEPWpZc2oxHK+WVtVgLf+xapVQ+eicRN/lRh4FxEZuEuY6+ucmM7QIjS+JSLvIvQ5O7B1bW3GfHUdfIrKjl6ePzH1wL4hDsYLi3P2Tc2xcxebOU5XVN2zbGtThaWF04w/hecIWqd1HrFkW+5w0mCO+Mh60xFmZyE1KaA8FLafvx59AkEEekFs4T0/DU3Zydj9vHAdCVGB6Mr/BoMyeBwK7C+JS3kwbHe7wcFAGxmh4eOzvWfkag9kvuMzfQa5oUlsx1PAhw9rVkyo7l6IgrQ6h/GZqkCJkMjVLhD5H3TXq5xo/nvzcbKW4A0oAIqeYE9tQgbEUDDkcdG3nNbL2HOhLMkf9Jjd7tkm8fsULsPEFcjoyaXDaPZPDo/Uam4HEf4M+hyYVRiVvitTE8a6ju3U7DPt7/l1MlfOuCztCV73MBVHXGbGXB9ZJimkF9Qbjr5u0Wns20/jHj/RswwEF7H8lL+ZPKmBsU07q8dGrRB/LH0GQWTEk9cp4JEQ+iUFJn8/vH819MYrhSs6PpDcWe6xBsP6vikJSeKSGw1luriUbC5ghv1ucLd2kmAmtelENWKHRAcPxXMtP3sg7ze2jSeIFIl0dSbrIEzYmMZREEQ2L6eAXUibCBquk2R8GzqfcdkayNUYXWZDI3XMzYq2ScU5EbyT1cu0YCp2YqvDDpkR0D26MA3A5PUAOQ+sc1KHKEWt+ZE3hRkRBaFj4IpX5HoEFlHk4t9eP5/2pZ9Nw3l9K+bjv6bj/TuSJQt6940n0Wh7eVGhYQHS/gTuT2GADeVzrdiia0l9e+htk6eCIM6q2l0YMQO4bEUucU7Y6UuRcMga5j5JuF0Zn1sfHcFf38/RdFbG1HwqdhPY8LF2gI8hbCqEJHX+Z1hbPXWW5a7KutRllzIPRV6bUiFXpNGybLOsvdR264Ac917S71RFiJGoPJNVhuFByawaH2Aps73n221KslWE8/vX4yJvnd2BzuuAdGcmpqohEYoh2FOIibC3lBysbkFyqxVxAJEaGzE4mAqdIQSZDSEZj3BJM5L7mndYJiKfWBWrNsGDrrDHPhvA65IDiyCDXAwEr1mj5+/2m0gZyBkNDzmEk8kGud7Q7Ctg2I2aTjXqJT13iaW4voB7LWcw6ArUdEF7jhFsDjKIYAK4mXIkWjubNIbtaGQV+b4VxGsAta+b3ZGSXSzBuLksTSP97/NGC1BKysd53XHl972TehHBwSuRAi9N0wq1ntBvGuQJNmfZiltsn/58VQRWqvbcjadjrvUcgeHYi/BO/S3nJOvq9bd8z0nXrgKvaxijUcCItjP6JqH5//5RiUrJRmnTe1tZc/S1/RGlCd0ScsIHNaKG9UDXyR6sOTXC0l6uiUkvtohJLseYPB+MXzylwJY0svFwnLp1lH1LvakP6GjRLReiZjIgwqxygs39F/3P+3ee1Fn3EomnkHmFv1vLIccWDlYaA3WMS83eB+EP/B/qS+Uq6l0C/myXtokmiF8cwipmf4wxoRPXcImI733aD71ZeIioQ/+tPp/8y2kXUSTh1oe9xnFw/z+j90caqeiG3tLOWidaJb91nC89pvdP8GoSv0gBQhq2hm2ucuMl3s3bk/hyaVnHdB4VKItL5Gw8S+67a+EVVlrYKrByX9nWTPy2wCG7Np+IGL2v5x/pdNcybnNplYm3cWLSbOHhZZ7b6FMyilrZlHOZGse2PXgczWrMe/D03m3Tujoq3pHHbe8PqAboEil84IAe1itR25KQS9PIPXvs3c8YdlX/AxthUd/Jxw6Oj35333qzEx9N1GI5HfWViDgXAVpHEUGl2X3HOOfx+aLFvCJSomHKEGsUCDHUS8ZvPD0rlBh9mZZnOUDL3LLKiD3j6//jNZzxzUlRcIO+c6I2hFTKzXnVsBUk9ki8oRXkfpmkGNy6lm335ZIf3L0ORF5eoY8QhuF7cO9Pwwr37F4C+rQQ7d8oEKlkvlbfeCAbEQPl7/3VdZonGGIrUBEhOl4jwYCNGGRoqyzusqYwe5vToaeNt3hHykzZ53rZcl/WVoUmew5dj6Aebc5mS/Oee0/MyVqsvDdp4zwHYNRGeZjWjnPj4///Iz6Ylon1lEa5BnQ+MoA8q5EMKDqtSVjfTXU8kBt4as1Jx86A0RMlHB/Dk0qSjxvT9PRxSVUTM0hQ1m62Njs7ZQb3ADVIBZYYOWVyijPh/H/0CtdONYNIhg8ExHptmecJUIi8mE42Hv45rFsGweXKRbOYJj+zI28+JVDn8MTTZmLLqK8rzLACebF6QRhQaeQ9DW8TT4aTxE924Esu+hI/h4/JfQsw1IejXnvg9bqgqyX6nPwbfoG7RRdJzBbYl2TstDX8zxYKCHeOjR/OJ+DU1iCA1zABbXFFBFeLuGx9iHO+LA92NXwReMKm5cApjWP5n/j9e/doM6Twj1sTNAZr4fg8LSUs8mxmXb8vXzHRXvx20Flltt2ZxDB4SH6jVmFyj8DE3W5NbZTmkDv45ZWNB40KgTpebVPac0CnnESBhPkTzknjB8mo/nfxwTM/SlzBAIzFv/9kIJOn9kMZEiWtlPJCtLePdpzJI973OY5Uq4/oDUZ6aIyAwFft9pW1J6J4YYvJoHxkcVniOvdpGXfdo+pT9XfnAfr3PPoD+e/2uz3kH310vDcsW1xMXOa0CWSfB8Pl548HO4P/1c1fBgLEQb6OT1zJIBqYywjvs1rwfpnVcDF4/b/MleoxPo+Od3C4BE0xm1TQeI4Rb4WGZfODwlfB4AEzhf7JmJcBJQ8zGGhePuhFf+wGxt34OYk4pmPzSe/by7Or3yzIEPk1+j1JR2IPuPHftN4DtrnjpwzdZ/sh8O4hyNX9b54XNq2I5xd10kRoejfRz/ohW7easN19f7LGIYJ9XosE6Hzv491G+59tb01DAsCvWox/+6u+J+lsZNix6DxPsKWZVStImlNOI2KyGPlH1AfnWHarBjdJ1D1Prg9VAuxVko/Xj/146PoL3XerU/NxIwxldYRtyjvm8bA4wbvbevizN6DouBioAwCH+wFq4QwWM4qFKj6kexomcfmzDg9hMMAqZUl1XrGvjyhL27BIudd60iLzSz3taPj/e/vu5DvlFgWwV7T7OTBLpjyG6vXZUDtiuVe9t7ree83tXOC04RIYEzlYE8rt7HVu2C7Hl46SwhQwrmmWKyLqDqCGxm1tflwfgnDoTSwVwg15/Oz+3j62d1LBDOvLe4mnctLxb03zPbpfm68e1OsO3iWCibYw2DjtPib/VNEUTwkXPKGaJhtyP8IzB7Yw3ByMDwJbV1RFdDQgETpVqAQenNWja7LNiP5/t4/QsoWiWHsbXY53eA0cDhikhiBmhUYjL5/jwk98YqY8C85ghua/ezlF/315CV8KvQ978je0QrQhA8mSHix/xTL7xn/wPDj2D4OZStLl4HXZ+Pw5+ZxkPtzCs+mewz74MrlQX9NcbrXaQGcZ2HhMRwpmonCnKvObW8RkTIrCl+Ogzj6BO6n5c5R23c7JN4MpKl+S0/cwaWcmFHInl2VbOBcGE7Ug8PAqvn4/j3xIOcFyDMQZ9cJhf6uZMK/z+NI8QH7G4J2+0w2mVljb20k2R+b5Jx5batryEAIceyUF5IKT6+b7XryJEursS8CJHUtj1IebsZN7RTtC1NAr0K4T/e//Q4eaNjts4Rmd+ncROEfNwjCN41Ivky0JELh2y1bSOX/VWJ0coOu+z9ZfzOpM5Whs7IYhdNkBSDpM2YBfdqQcxjNwa+Wh8K5F0+CzS9Z2L2CsQV/fH1cwkyV1JzFUtnA+023gjm5w0nczhxHxt68VRUW5RSm1t3xADNKUmLlzn4NXiljtxXav3aDSOUIW5OK3pQksTalBPiCcFLEGfissHeVEWMLAfCAcH5x+s/s6V76V5Sf6hE3aU9tARSpXVeesOuY6+Sp7PMB6UmRA68BIknaTc0+FMVy0q9HN+Uj+0mSKXmVakbR+C7HFsR+4LhY3IIw82mgYo8+pKLoR7Xv34e/ok0fdqFGJ7taKKwzjuv/PJscEFa8LQlkljUWhY7dK5RP4QTsff3HQ6e83mZ72sxK8azdTbCHVurqczW6IYM4UT1mWM0v8ac2vPQ3SpkhJVCIyF93v9lPsdzYW1oobn/6kczY17nHuaXOHU587y1lRviuIjfgs9V6XmHh0I7ZgsiWZBpPdZEpws9yuIcgsE0ke2KJqGOkt7XfL5D/ZPSM7vE95pnXdh+/P6bV2dqBmhTSVhVDpORIjFBNUYef3I0BtcSe/zh3OtB5JfpbGqfd7hU8M7hlt10Njwd7y9OwaAgjVz7pPXzq1KldMf7DphhfAzGaajMzT6JVC6aV28+Pv94jJXPr7xZvObIe+e3twBtLAdKsntnZ33Jdn4p6l0PF9HmcyE/d/jo91ibiYHm6JgeR5dGsKVsITeOhlWc1nxDbuEWZu+zhTouQG1xJa7B6IeUsX/c/9NSBhd1Pwculo86r+hhQuu81rrMzA9FI0ccg2cneVirROX/dYdTV7rkmceKRCmMmDIx19G1GYlWtYhhZ1es4FCOs7Jxjb3nq8/Iks8LA80Wc5QfP3/CtpVA5WciKartquepc1zWVPLi9HveAeqrZjNn94lvAtH+zx1eEHc6Xuu8IgCV3Xu5GKpkI7MVGCHPhnTgfaksbsZ5V0ZLdgiPwoRYlBI0loN8PPuNQisEoOiuwjiIaT2PLTu0CLNYCTUcbD0veGzq8453lZbl9x1us13sIAoZ4CtT29O8LHvVngCvL9CU4lYAofu7Kzw8DdjMCKSuwG8gHp/i3ufo1IdlTnD5Xk///ha82fmOT3YLcVK2IKMTd0gBRjP73YHfPW/9jzv8YH5rklLPA3dD38/tspR1wqbjGWuhakWYE3z7iXHPqY7UFASCS1Yszwvgzyo/3v/+eGvh3H1RkHjBVbnpEwacL03b/N4DxMLhgT2dC6TVsHD9vsrmPeeKkAgezl54+kIWy4/3F97aS3irp9NA8FuQ8s5Jmb7UWUJdFlSqpuKekAeZj+f/+tFLcQXJLgLhvYBQ1tt3G/+8w9NBR1z0mlfCz4uB2OI5+eMOzzJTHrOX5UFc6JNZXJzfeT3HqPBHave+zOnH9dWiwk3uQBrijHTUgraEdgNEf778gw56ziuy2cxCDsS6XLefrPy8w9WshffZ6zbL22uZNkz+uMqm2lLfX3L9bp1sfFVBz68QPBEKornLfKayIYK4O7oSwTiZXzHcZ+lz3o35xkOfh/+/5CALPupWQol+5iy2ua4ZoMuYX/8mZpnk1Wpw8S9X2dSNyndhAPlPILyasEgMEjPJ2/v+vgFJYJjI8nXY+RW79bgx6s2kyfu3CMjP9/9/5Stf+cpXvvKVr3zlK1/5yle+8pWvfOUrX/nKV77yla985Stf+cpXvvKVr3zlK1/5yle+8pWvfOUrX/nKV77yla985Stf+cpXvvKVr3zlK1/5yle+8pWvfOUrX/nKV77yla985Stf+cpXvvKVr3zlK1/5yle+8pWvfOUrX/nKV77yla985Stf+cpXvvKVr3zlK1/5yle+8pWvfOUrX/nKV77yla985Stf+cpXvvIVgP8H3ZoZmXcppvcAAAJsbWtCVPrOyv4Af+LgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB4nO3bXXHjMBQGUEMIBEPoTAkYQiEEQiAEQiAUQiEEQB8CoRCWQTd3Ks+6M90msWXLP+fM6F3yd30luc1n9fz+WWAcr6Oa0EvPee4nnufUSmRfIv96wFzXXANbyT+cB8z3daU1sKX8m4FzfruO3crqYEv5h9PAeV+u42lFNbC1/MNl4Nz/VOs5E2wx/116j4euIc4ES98Ptph/yFUDH9Wy94NS+Z9n8Mx2aR5rqOe+tpx/6zXTmqIXNDNa1z3k/2WfcW1LOhfI/5+n9A7nWF/cEZawJ8j/u3hv3zKuM+ppzndF+f9sn97htdeB/P+vrvLdD+ZaB/K/7ZC5F3TroPQ5Uf73yX0uaEfUVfxNoi70POT/mKbKd0f46ZlMvTfIv59jlX9P6PaE+IYwxXdl+fdXp5zGfE7Ra04j1oL8hxvjnvBbX8h5bpR/Ps1EddCOS+oNUQ99z4/yzy/qYIy7wj39IZ7rMdVEzONWn5D/eKY4HzzSK86pLo+dIf/xxTsY35DGujcucWwp/67oyXPpCfIvJ3pC7NElzgnd0RQaS/6fudzqgrVQeu18F30hfqsYe8QU54XS6+V30RsOI9ZD6fXxmDr1h7i7xRlq6N8gSq+H4XbpTHXo1MW9v20oPXfG1dZGk86Y7XefU+UOBgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADMy182qYahk9m84gAAATpta0JU+s7K/gCADcYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHic7dlBDcJAFEXRSkICUlihAxu4qRScDLAl7Y6kk95zkmfg39nNsvDrujzv4+RbPzv6zrPSv03/Nv3b9G/Tv03/Nv3b9G/Tv63Q/6X/rkL/of8u/dv0b9O/Tf82/dv0b9O/Tf82/dv0b9O/Tf82/dv0b9O/Tf82/dv0b6v0v3gDmyr9r/pvuk3QRv/jPCZoo/9x9G/Tv03/Nv3b9G/Tv03/tkr/of8m/dv0b9O/Tf+27//fGtm/bgYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFMbY5iZmZmZmZmZ2Xn3BrYSlGCOuFS0AAADIm1rQlT6zsr+AIAmlQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAeJzt3cFto0AYQGFKSAkugRIoISX4tGeX4BJ83ZtLSAkuISWkBHfgZaRZKSHBYRAwM7z3SXO38n7DABZpGqE8Ho8vSyz2Z7M/m/3Z7M9mfzb7s9mfzf5s9mezP5v92ezPZn82+7PZn83+bPZnsz+b/dnsz2Z/Nvuz2Z/N/mz2Z7M/m/3Z7M9mfzb7s9mfzf5s9mezP5v92ezPZn82+7PZn83+bPZnsz+b/dnsz2Z/Nvuz2Z/N/mz2Z7M/m/3Z7M9mf7ZB/3Pz98+xX12/XvqV++NpZYP+j775cN36denXqV+tM7EvE/qPzcTZeajfzP6f171f1369Ogv1WaD/T7PQOQt1WLj/5/UR9wzuIws26H+I391jPL/f4nd6iWPCwTkoz8Tr/0OciWv8Xs+dBeegMDPv/7Tx+PDuHNRtgft/oeNlxnHhHmfI/UFGC9//PcY9Q+o+0euFTFa6/9/FY3zKHLx5LNjeys9/2sTjQTgneB9pQxs9/+sS94oXjwXb2Pj576mZfj8hzIvPF1aW4fn/IeGc4PlgZRl//5FyLDg7A+vI/PufNmFfcHUGllfA779e4rXf1D2B+8IFFdD/v7MzsL2C+gdHZ2BbhfUPuon7QmdgAQX2D1pnYBuF9g9SZiD3Z61Wwf2DqTNwcQbmKbx/0E7cE/oMeYYK+gdTrgs+7J+ukv7BlBnweVGiivoHv/2mxHNAosr6B2MzcPdaMF2F/YPX5uvvTW8e++eptL8WYn82+7PZn83+bPZnsz+b/dnsz2Z/Nvuz2Z/N/mz2Z7M/m/3Z7M9mfzb7s9mfzf5s9mezP5v92ezPZn82+7PZn83+bPZnG/Sf8p6dPa3cf/7s7M9mfzb7s4H7+97Q5lv/8B6dva2x94fe7I+4/hv7X0Nv9kf0H/v++z+FGkT/sfP/yf6779892f/5rtBm9/2fvTPYd4U2u+//7F2xuT9bEQb9/wFbZf32Gc47HAAAAzNta0JU+s7K/gCAYyMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHic7dzNccIwFEVhSqAESqAESqAEr7KmBJfANjtKoARKoARKoAOCBjEEYyTZGFt695yZt8/MF1nyTzKb2eo4+/25tMzhOlP/bFl0uVyexlCLN/ZuavxvGfavAv4r/G8Z9t8H/Kf+2bLJsP+ZvT+eUf/QtX+D/yOj/qFr/wL/Rwb9Q+f+I/bPGfSvufanZ8x/fvV9d+7j2t+SMf/QuW+P/WvG/E8B/zX+rxny3wTsT9i3Z8Q/tu9X+LdnxD905mftBzLgH7rfZ+1HMuB/YO33r3D/dWTtc+aPVLB/7MzHe76ECvYPvePhWV9ihfqH7vX5vqtDBfovI/a84+tQYf6xPf/Mdb9bBfnP/drmXn/ACvLfRex32HevEP+YPXt+zwrwT7Gf49+vzP1j9u68t8S+fxn7Yz9CGfqnnPOxH6jM/Jf+nR32I5WRfxV5toP9F8rAf+7v3UPu93f52A/cxP6rhOs993hfbCJ/Z7lNcOe53pebwD9ln+d5/kiN6L9KuK9jrx+5Efyde+gbzeZs2evH64v+VUd3t+b5vzwjN7C/W7d14pn+/9Ss+WkayN99Zx37HrNt3PWB73Um7AP/tb83Sz3LN9251mdQB3+3Tjd+nfcxv7vzNxkZ9cZ/4ddn/aE36z3zGv5dzuuxOfv9gf094xr+Q7i736GK83wZDeR/9GcD1nph9fQ/+3NBhXnZJfqfvLdb4zyXN1TL+e/gz/0bzuz2y+D7H5ow/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDXzv8tcNfO/y1w187/LXDX7umP8MwDMMwDMMwDMMwDGN6/gBANv23/I0AxgAAMiFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+Cjx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIj4KICAgICAgICAgPHhtcDpDcmVhdG9yVG9vbD5BZG9iZSBGaXJld29ya3MgQ1M1IDExLjAuMC40ODQgV2luZG93czwveG1wOkNyZWF0b3JUb29sPgogICAgICAgICA8eG1wOkNyZWF0ZURhdGU+MjAyNC0wNS0xNVQxNTowNDo0N1o8L3htcDpDcmVhdGVEYXRlPgogICAgICAgICA8eG1wOk1vZGlmeURhdGU+MjAyNC0wNy0wNFQwMDozMDoxNFo8L3htcDpNb2RpZnlEYXRlPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIj4KICAgICAgICAgPGRjOmZvcm1hdD5pbWFnZS9wbmc8L2RjOmZvcm1hdD4KICAgICAgPC9yZGY6RGVzY3JpcHRpb24+CiAgIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg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
\.


--
-- Data for Name: ouvidoria_dica; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_dica (id_dica, nome, texto, ativo) FROM stdin;
\.


--
-- Data for Name: ouvidoria_emissor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_emissor (id_emissor, nome, id_medida, id_tempo, emissao, descricao, ativo) FROM stdin;
5	Pessoas	5	3	0.041	edsfdsfsdafsd	t
\.


--
-- Data for Name: ouvidoria_entidade; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_entidade (id_entidade, id_criador, id_dono, id_alterador, tipo, descricao, data_criacao, data_modificacao, deleted, tag, label) FROM stdin;
126608	1	1	\N	Indicador		2024-09-17 02:26:43.109937	\N	f	Investimento	
126609	1	1	\N	Indicador		2024-09-17 02:26:43.109937	\N	f	Voluntários	
126610	1	1	\N	Indicador		2024-09-17 02:26:43.109937	\N	f	Beneficiários	
126611	1	1	\N	Indicador		2024-09-17 02:26:43.109937	\N	f	Parcerias	
126607	1	268	1	Projeto		2024-09-17 02:26:43.109937	2024-09-20 18:39:39	f	teste	
126612	1	1	1	Indicador		2024-09-17 02:26:43.109937	2024-09-20 18:39:39	f	Plantio de Árvores	
\.


--
-- Data for Name: ouvidoria_entidade_link; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_entidade_link (id_link, id_entidade, id_criador, id_alterador, descricao, data_criacao, data_modificacao, deleted, label, url) FROM stdin;
\.


--
-- Data for Name: ouvidoria_entidade_perfil; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_entidade_perfil (id_perfil, tipo, operacao, particular) FROM stdin;
2	Projeto	read	t
2	Projeto	create	t
2	Projeto	update	t
2	Projeto	delete	t
2	Indicador	read	t
2	Indicador	create	t
2	Indicador	update	t
2	Indicador	delete	t
3	Projeto	read	t
3	Indicador	read	t
3	Indicador	update	t
\.


--
-- Data for Name: ouvidoria_evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_evento (id_evento, hora_inicio, hora_fim, logo_marca, id_indicador_arvores) FROM stdin;
126607	22:00	23:00		126612
\.


--
-- Data for Name: ouvidoria_evento_emissor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_evento_emissor (id_evento, id_emissor, quantidade) FROM stdin;
126607	5	3000
\.


--
-- Data for Name: ouvidoria_historico; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_historico (id, campo, valor_antigo, valor_novo, id_alterador_novo, id_alterador_antigo, data, id_entidade) FROM stdin;
48784	inicio	2024-09-16	18/09/2024	1	1	2024-09-20 21:37:02.445889	126607
48785	fim	2024-09-16	22/09/2024	1	1	2024-09-20 21:37:02.445889	126607
48786	inicio	2024-09-18	20/09/2024	1	1	2024-09-20 21:37:26.30114	126607
48787	fim	2024-09-22	22/09/2024	1	1	2024-09-20 21:37:26.30114	126607
48788	previsto	1	0	1	1	2024-09-20 21:37:26.30114	126612
48789	inicio	2024-09-20	20/09/2024	1	1	2024-09-20 21:38:49.22858	126607
48790	fim	2024-09-22	22/09/2024	1	1	2024-09-20 21:38:49.22858	126607
48791	inicio	2024-09-20	20/09/2024	1	1	2024-09-20 21:39:17.132211	126607
48792	fim	2024-09-22	09/09/2024	1	1	2024-09-20 21:39:17.132211	126607
48793	inicio	2024-09-20	18/09/2024	1	1	2024-09-20 21:39:39.112665	126607
48794	fim	2024-09-09	27/09/2024	1	1	2024-09-20 21:39:39.112665	126607
48795	previsto	0	1	1	1	2024-09-20 21:39:39.112665	126612
\.


--
-- Data for Name: ouvidoria_login; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_login (id, id_usuario, data_hora) FROM stdin;
84	1	2024-07-01 15:18:39.370688
85	1	2024-07-02 12:52:25.951492
86	1	2024-07-02 13:21:49.286719
87	1	2024-07-02 14:58:59.861188
88	1	2024-07-02 18:17:07.190403
89	1	2024-07-02 22:43:37.839045
90	1	2024-07-03 22:05:58.915803
91	1	2024-07-03 22:31:59.733948
92	1	2024-07-03 23:51:37.497492
93	1	2024-07-04 17:23:00.884122
94	1	2024-07-05 02:51:00.511292
95	1	2024-07-05 12:01:36.497834
96	1	2024-07-05 19:05:03.010763
97	1	2024-07-09 22:08:43.749897
98	1	2024-07-10 12:46:48.657042
99	1	2024-07-10 16:35:51.954945
100	1	2024-07-10 19:17:15.388051
101	1	2024-07-10 22:27:03.7028
102	1	2024-07-13 16:26:56.287431
103	1	2024-07-13 16:44:11.093471
104	1	2024-07-13 20:19:32.281711
105	1	2024-07-16 19:25:22.477172
106	270	2024-07-16 20:23:09.645754
107	1	2024-07-16 20:48:42.249713
108	268	2024-07-16 21:17:39.821278
109	1	2024-07-16 21:59:25.597695
110	1	2024-07-17 14:53:28.373031
143	1	2024-07-17 15:45:20.486356
144	1	2024-07-25 17:03:47.411372
145	1	2024-08-07 21:57:40.31322
146	1	2024-08-07 23:53:21.423361
147	1	2024-08-08 02:14:12.133721
148	1	2024-08-08 13:30:35.878279
149	1	2024-08-09 19:19:47.592308
150	1	2024-08-09 20:16:01.806032
151	1	2024-09-13 23:50:02.044967
152	1	2024-09-14 11:25:55.577372
153	1	2024-09-14 19:10:43.228809
154	1	2024-09-14 22:19:05.83787
155	1	2024-09-16 00:23:54.206314
156	1	2024-09-16 19:57:16.270717
157	1	2024-09-20 21:36:19.96124
158	1	2024-09-21 22:43:45.821051
159	1	2024-12-03 19:31:17.223807
160	1	2024-12-14 20:25:45.186073
161	1	2025-10-10 10:11:45.919151
\.


--
-- Data for Name: ouvidoria_medida; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_medida (id_medida, nome, ativo) FROM stdin;
5	Unidade	t
\.


--
-- Data for Name: ouvidoria_objetivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_objetivo (id_objetivo, nome, descricao, id_objetivo_grupo) FROM stdin;
2	Fome zero agricultura sustentável		1
6	Água potável e saneamento		1
7	Energia limpa e acessível		1
12	Consumo e produção sustentáveis		1
13	Vida na água		1
14	Vida terrestre		1
15	Paz, justiça e instituições eficazes		1
1	Erradicação da pobreza		2
3	Saúde e bem-estar		2
4	Educação de qualidade		2
5	Igualdade de gênero		2
8	Trabalho decente e crescimento econômico		2
10	Redução das desigualdades		2
11	Cidades e comunidades sustentáveis		2
9	Indústria, inovação e infraestrutura		3
16	Ação contra a mudança global do clima		3
17	Parcerias e meios de implementação		3
\.


--
-- Data for Name: ouvidoria_objetivo_grupo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_objetivo_grupo (id_objetivo_grupo, nome, descricao) FROM stdin;
1	Ambiental	\N
2	Social	\N
3	Governança	\N
\.


--
-- Data for Name: ouvidoria_perfil; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_perfil (id_perfil, descricao) FROM stdin;
1	Administrador
2	Responsável
3	Membro
\.


--
-- Data for Name: ouvidoria_projeto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_projeto (id, nome, inicio, fim, id_tipo_projeto) FROM stdin;
126607	teste	2024-09-18	2024-09-27	2
\.


--
-- Data for Name: ouvidoria_projeto_indicador; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_projeto_indicador (id, id_projeto, numero_indicador, nome, id_tipo_indicador, valor, previsto, vermelho, amarelo) FROM stdin;
126608	126607	1	Investimento	3	0	0	20	50
126609	126607	2	Voluntários	1	0	0	20	50
126610	126607	3	Beneficiários	1	0	0	20	50
126611	126607	4	Parcerias	1	0	0	20	50
126612	126607	5	Plantio de Árvores	1	0	1	20	50
\.


--
-- Data for Name: ouvidoria_projeto_objetivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_projeto_objetivo (id_projeto, id_objetivo) FROM stdin;
126607	13
\.


--
-- Data for Name: ouvidoria_projeto_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_projeto_usuario (id_projeto, id_usuario) FROM stdin;
126607	270
\.


--
-- Data for Name: ouvidoria_tempo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_tempo (id_tempo, nome, ativo, multiplicador) FROM stdin;
1	segundo	t	1
2	minuto	t	60
3	hora	t	3600
4	evento	t	0
\.


--
-- Data for Name: ouvidoria_tipo_indicador; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_tipo_indicador (id_tipo_indicador, nome) FROM stdin;
1	Número
2	Decimal
3	Moeda
\.


--
-- Data for Name: ouvidoria_tipo_projeto; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_tipo_projeto (id_tipo_projeto, nome) FROM stdin;
1	Default
2	Emissao CO2
\.


--
-- Data for Name: ouvidoria_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ouvidoria_usuario (id_usuario, nome, email, senha, ativo, id_perfil, data_expiracao_login, codigo_vendedor, telefone, departamento) FROM stdin;
1	Leandro	batisti_bsi@hotmail.com	64884d9d9dcc713da3f3a55bd4487406	t	1	\N	\N	\N	\N
268	responsavel 1	responsavel1@bemfeitosistemas.com.br	81dc9bdb52d04dc20036dbd8313ed055	t	2	\N	\N	\N	\N
269	responsavel 2	responsavel2@bemfeitosistemas.com.br	81dc9bdb52d04dc20036dbd8313ed055	t	2	\N	\N	\N	\N
271	Leandro Bunick Batisti Kalckmann Araújo Silva	membro2@bemfeitosistemas.com.br	81dc9bdb52d04dc20036dbd8313ed055	t	3	\N	\N	(41) 99963-4288	fdsafdasf
270	membro 1	membro1@bemfeitosistemas.com.br	81dc9bdb52d04dc20036dbd8313ed055	t	3	\N	\N	\N	teste
\.


--
-- Name: ouvidoria_dica_id_dica_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_dica_id_dica_seq', 3, true);


--
-- Name: ouvidoria_emissor_id_emissor_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_emissor_id_emissor_seq', 5, true);


--
-- Name: ouvidoria_entidade_id_entidade_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_entidade_id_entidade_seq', 126612, true);


--
-- Name: ouvidoria_entidade_link_id_link_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_entidade_link_id_link_seq', 209, true);


--
-- Name: ouvidoria_historico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_historico_id_seq', 48795, true);


--
-- Name: ouvidoria_login_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_login_id_seq', 161, true);


--
-- Name: ouvidoria_medida_id_medida_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_medida_id_medida_seq', 5, true);


--
-- Name: ouvidoria_perfil_id_perfil_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_perfil_id_perfil_seq', 1, true);


--
-- Name: ouvidoria_tempo_id_tempo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_tempo_id_tempo_seq', 4, true);


--
-- Name: ouvidoria_usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ouvidoria_usuario_id_usuario_seq', 271, true);


--
-- Name: ouvidoria_config ouvidoria_config_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_config
    ADD CONSTRAINT ouvidoria_config_pkey PRIMARY KEY (nome_empresa);


--
-- Name: ouvidoria_dica ouvidoria_dica_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_dica
    ADD CONSTRAINT ouvidoria_dica_pkey PRIMARY KEY (id_dica);


--
-- Name: ouvidoria_emissor ouvidoria_emissor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_emissor
    ADD CONSTRAINT ouvidoria_emissor_pkey PRIMARY KEY (id_emissor);


--
-- Name: ouvidoria_entidade_link ouvidoria_entidade_link_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_entidade_link
    ADD CONSTRAINT ouvidoria_entidade_link_pkey PRIMARY KEY (id_link);


--
-- Name: ouvidoria_entidade_perfil ouvidoria_entidade_perfil_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_entidade_perfil
    ADD CONSTRAINT ouvidoria_entidade_perfil_pkey PRIMARY KEY (id_perfil, tipo, operacao);


--
-- Name: ouvidoria_entidade ouvidoria_entidade_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_entidade
    ADD CONSTRAINT ouvidoria_entidade_pkey PRIMARY KEY (id_entidade);


--
-- Name: ouvidoria_evento_emissor ouvidoria_evento_emissor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_evento_emissor
    ADD CONSTRAINT ouvidoria_evento_emissor_pkey PRIMARY KEY (id_evento, id_emissor);


--
-- Name: ouvidoria_evento ouvidoria_evento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_evento
    ADD CONSTRAINT ouvidoria_evento_pkey PRIMARY KEY (id_evento);


--
-- Name: ouvidoria_historico ouvidoria_historico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_historico
    ADD CONSTRAINT ouvidoria_historico_pkey PRIMARY KEY (id);


--
-- Name: ouvidoria_login ouvidoria_login_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_login
    ADD CONSTRAINT ouvidoria_login_pkey PRIMARY KEY (id);


--
-- Name: ouvidoria_medida ouvidoria_medida_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_medida
    ADD CONSTRAINT ouvidoria_medida_pkey PRIMARY KEY (id_medida);


--
-- Name: ouvidoria_objetivo_grupo ouvidoria_objetivo_grupo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_objetivo_grupo
    ADD CONSTRAINT ouvidoria_objetivo_grupo_pkey PRIMARY KEY (id_objetivo_grupo);


--
-- Name: ouvidoria_objetivo ouvidoria_objetivos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_objetivo
    ADD CONSTRAINT ouvidoria_objetivos_pkey PRIMARY KEY (id_objetivo);


--
-- Name: ouvidoria_perfil ouvidoria_perfil_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_perfil
    ADD CONSTRAINT ouvidoria_perfil_pkey PRIMARY KEY (id_perfil);


--
-- Name: ouvidoria_projeto_indicador ouvidoria_projeto_indicador_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_indicador
    ADD CONSTRAINT ouvidoria_projeto_indicador_pkey PRIMARY KEY (id);


--
-- Name: ouvidoria_projeto_objetivo ouvidoria_projeto_objetivo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_objetivo
    ADD CONSTRAINT ouvidoria_projeto_objetivo_pkey PRIMARY KEY (id_projeto, id_objetivo);


--
-- Name: ouvidoria_projeto ouvidoria_projeto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto
    ADD CONSTRAINT ouvidoria_projeto_pkey PRIMARY KEY (id);


--
-- Name: ouvidoria_projeto_usuario ouvidoria_projeto_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_usuario
    ADD CONSTRAINT ouvidoria_projeto_usuario_pkey PRIMARY KEY (id_projeto, id_usuario);


--
-- Name: ouvidoria_tempo ouvidoria_tempo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_tempo
    ADD CONSTRAINT ouvidoria_tempo_pkey PRIMARY KEY (id_tempo);


--
-- Name: ouvidoria_tipo_indicador ouvidoria_tipo_indicador_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_tipo_indicador
    ADD CONSTRAINT ouvidoria_tipo_indicador_pkey PRIMARY KEY (id_tipo_indicador);


--
-- Name: ouvidoria_tipo_projeto ouvidoria_tipo_projeto_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_tipo_projeto
    ADD CONSTRAINT ouvidoria_tipo_projeto_pkey PRIMARY KEY (id_tipo_projeto);


--
-- Name: ouvidoria_usuario ouvidoria_usuario_codigo_vendedor_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_usuario
    ADD CONSTRAINT ouvidoria_usuario_codigo_vendedor_key UNIQUE (codigo_vendedor);


--
-- Name: ouvidoria_usuario ouvidoria_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_usuario
    ADD CONSTRAINT ouvidoria_usuario_pkey PRIMARY KEY (id_usuario);


--
-- Name: ouvidoria_emissor ouvidoria_emissor_id_medida_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_emissor
    ADD CONSTRAINT ouvidoria_emissor_id_medida_fkey FOREIGN KEY (id_medida) REFERENCES public.ouvidoria_medida(id_medida) NOT VALID;


--
-- Name: ouvidoria_emissor ouvidoria_emissor_id_tempo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_emissor
    ADD CONSTRAINT ouvidoria_emissor_id_tempo_fkey FOREIGN KEY (id_tempo) REFERENCES public.ouvidoria_tempo(id_tempo) NOT VALID;


--
-- Name: ouvidoria_entidade_link ouvidoria_entidade_link_id_entidade_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_entidade_link
    ADD CONSTRAINT ouvidoria_entidade_link_id_entidade_fkey FOREIGN KEY (id_entidade) REFERENCES public.ouvidoria_entidade(id_entidade);


--
-- Name: ouvidoria_entidade_perfil ouvidoria_entidade_perfil_id_perfil_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_entidade_perfil
    ADD CONSTRAINT ouvidoria_entidade_perfil_id_perfil_fkey FOREIGN KEY (id_perfil) REFERENCES public.ouvidoria_perfil(id_perfil);


--
-- Name: ouvidoria_evento_emissor ouvidoria_evento_emissor_id_emissor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_evento_emissor
    ADD CONSTRAINT ouvidoria_evento_emissor_id_emissor_fkey FOREIGN KEY (id_emissor) REFERENCES public.ouvidoria_emissor(id_emissor) NOT VALID;


--
-- Name: ouvidoria_evento_emissor ouvidoria_evento_emissor_id_evento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_evento_emissor
    ADD CONSTRAINT ouvidoria_evento_emissor_id_evento_fkey FOREIGN KEY (id_evento) REFERENCES public.ouvidoria_evento(id_evento) NOT VALID;


--
-- Name: ouvidoria_evento ouvidoria_evento_id_evento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_evento
    ADD CONSTRAINT ouvidoria_evento_id_evento_fkey FOREIGN KEY (id_evento) REFERENCES public.ouvidoria_projeto(id) NOT VALID;


--
-- Name: ouvidoria_historico ouvidoria_historico_id_alterador_antigo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_historico
    ADD CONSTRAINT ouvidoria_historico_id_alterador_antigo_fkey FOREIGN KEY (id_alterador_antigo) REFERENCES public.ouvidoria_usuario(id_usuario);


--
-- Name: ouvidoria_historico ouvidoria_historico_id_alterador_novo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_historico
    ADD CONSTRAINT ouvidoria_historico_id_alterador_novo_fkey FOREIGN KEY (id_alterador_novo) REFERENCES public.ouvidoria_usuario(id_usuario);


--
-- Name: ouvidoria_historico ouvidoria_historico_id_entidade_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_historico
    ADD CONSTRAINT ouvidoria_historico_id_entidade_fkey FOREIGN KEY (id_entidade) REFERENCES public.ouvidoria_entidade(id_entidade);


--
-- Name: ouvidoria_login ouvidoria_login_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_login
    ADD CONSTRAINT ouvidoria_login_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.ouvidoria_usuario(id_usuario);


--
-- Name: ouvidoria_objetivo ouvidoria_objetivo_id_objetivo_grupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_objetivo
    ADD CONSTRAINT ouvidoria_objetivo_id_objetivo_grupo_fkey FOREIGN KEY (id_objetivo_grupo) REFERENCES public.ouvidoria_objetivo_grupo(id_objetivo_grupo) NOT VALID;


--
-- Name: ouvidoria_projeto ouvidoria_projeto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto
    ADD CONSTRAINT ouvidoria_projeto_id_fkey FOREIGN KEY (id) REFERENCES public.ouvidoria_entidade(id_entidade);


--
-- Name: ouvidoria_projeto ouvidoria_projeto_id_tipo_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto
    ADD CONSTRAINT ouvidoria_projeto_id_tipo_projeto_fkey FOREIGN KEY (id_tipo_projeto) REFERENCES public.ouvidoria_tipo_projeto(id_tipo_projeto) NOT VALID;


--
-- Name: ouvidoria_projeto_indicador ouvidoria_projeto_indicador_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_indicador
    ADD CONSTRAINT ouvidoria_projeto_indicador_id_fkey FOREIGN KEY (id) REFERENCES public.ouvidoria_entidade(id_entidade);


--
-- Name: ouvidoria_projeto_indicador ouvidoria_projeto_indicador_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_indicador
    ADD CONSTRAINT ouvidoria_projeto_indicador_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES public.ouvidoria_projeto(id);


--
-- Name: ouvidoria_projeto_indicador ouvidoria_projeto_indicador_id_tipo_indicador_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_indicador
    ADD CONSTRAINT ouvidoria_projeto_indicador_id_tipo_indicador_fkey FOREIGN KEY (id_tipo_indicador) REFERENCES public.ouvidoria_tipo_indicador(id_tipo_indicador);


--
-- Name: ouvidoria_projeto_objetivo ouvidoria_projeto_objetivo_id_objetivo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_objetivo
    ADD CONSTRAINT ouvidoria_projeto_objetivo_id_objetivo_fkey FOREIGN KEY (id_objetivo) REFERENCES public.ouvidoria_objetivo(id_objetivo) NOT VALID;


--
-- Name: ouvidoria_projeto_objetivo ouvidoria_projeto_objetivo_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_objetivo
    ADD CONSTRAINT ouvidoria_projeto_objetivo_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES public.ouvidoria_projeto(id) NOT VALID;


--
-- Name: ouvidoria_projeto_usuario ouvidoria_projeto_usuario_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_usuario
    ADD CONSTRAINT ouvidoria_projeto_usuario_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES public.ouvidoria_projeto(id) ON DELETE CASCADE;


--
-- Name: ouvidoria_projeto_usuario ouvidoria_projeto_usuario_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_projeto_usuario
    ADD CONSTRAINT ouvidoria_projeto_usuario_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.ouvidoria_usuario(id_usuario) ON DELETE CASCADE;


--
-- Name: ouvidoria_usuario ouvidoria_usuario_id_perfil_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ouvidoria_usuario
    ADD CONSTRAINT ouvidoria_usuario_id_perfil_fkey FOREIGN KEY (id_perfil) REFERENCES public.ouvidoria_perfil(id_perfil);


--
-- PostgreSQL database dump complete
--

