create table service(
  endpoint varchar(250) not null,
  endpointUrl varchar(20) not null,
  constraint pk_service primary key(endpoint, endpointUrl)
);
