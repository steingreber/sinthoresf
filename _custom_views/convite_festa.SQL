SELECT pessoas.cod_pessoa AS cod_pessoa,
  pessoas.nome AS nome,
  empresas.nome_empresa AS nome_empresa,
  socios.acompanhante AS acompanhante,
  socios.foto AS foto,
  socios.validade AS validade,
  socios.cod_socio
FROM (pessoas
  JOIN socios ON pessoas.cod_pessoa = socios.socio)
  JOIN empresas ON socios.cod_empresa = empresas.cod_empresa
