CREATE TABLE rsses (
    id          char(36)    ,   -- UUID(UniqueIndex)
    created     timestamp   ,   -- 登録日時
    title       varchar(50) ,   -- タイトル
    link        varchar(500),   -- URL
    description varchar(500),   -- 100
    dc_date     timestamp   ,   -- 日付
    username    varchar(50) ,   -- ユーザー名
    server_no   smallint    ,   -- サーバー番号
    entry_no    smallint    ,   -- エントリーNo.
    PRIMARY KEY (id)        ,
    KEY dc_date (dc_date)   ,
    KEY username (username) ,
    KEY server_no (server_no),
    KEY entry_no (entry_no)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
