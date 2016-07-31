create table microservice_user(
  username varchar(45) not null,
  password varchar(128) not null,
  constraint pk_user primary key(username, password)
);