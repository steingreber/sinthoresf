SELECT pessoas.cod_pessoa AS cod_pessoa,
  pessoas.nome AS nome,
  pessoas.cpf AS cpf,
  empresas.nome_empresa AS nome_empresa,
  config.valor_mensal AS valor_mensal,
  config.valor_extenso AS valor_extenso,
  config.A21EMPRESA AS A21EMPRESA,
  config.A15ENDRECO AS A15ENDRECO,
  config.A16CIDADE AS A16CIDADE,
  config.A17ESTADO AS A17ESTADO,
  config.A19FONE AS A19FONE,
  config.A08NOME AS A08NOME,
  socios.cod_socio AS cod_socio,
  config.A04imagem
FROM (pessoas
  JOIN socios ON socios.socio = pessoas.cod_pessoa)
  JOIN empresas ON socios.cod_empresa = empresas.cod_empresa,
  config
