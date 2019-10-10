CREATE TABLE user(
  id int unsigned primary key auto_increment,
  username varchar(64) unique not null default '',
  password varchar(64) not null default '',
  nikename varchar(64) not null default ''
)charset utf8;
# ALTER TABLE user ADD idt timestamp default NOW() AFTER nikename;
ALTER TABLE user ADD udt timestamp default NOW() AFTER udate;
INSERT INTO user VALUES (DEFAULT,'wwj','123456','文君','2019-09-30 17:46',DEFAULT);
ALTER TABLE user ADD login_times int(11) not null default 0 AFTER last_login_time;
ALTER TABLE user ADD status smallint(1) not null default 1 AFTER login_times;
ALTER TABLE `user` MODIFY COLUMN `idt` varchar(50)comment '注册时间';
desc links;
show tables;
show create table user; # 查看表详细信息
show full columns from links; # 查看表字段的详细信息
show full columns from links;
show full columns from category;
insert into category values(default,'计算机',default,default);
show full columns from article;
insert into category  values(default,'奇瑞瑞虎5',default,5);
select * from category;

insert into article  select * from article;

# 蠕虫复制
insert into article(category_id, user_id, title, content,addate) values(8,7,'哈哈哈','嘿嘿嘿',1587542467);
INSERT INTO `article`(category_id,user_id,title,content,addate) SELECT category_id,user_id,title,content,addate FROM article;

SELECT article.*,user.nikename,category.classname FROM article
LEFT JOIN user ON article.user_id=user.id
  LEFT JOIN category ON article.category_id=category.id WHERE article.id=256;

# 多表关联删除
delete category,article from category
left join article on category.id = article.category_id
where category.id=20;

#同一张表无线级删除
delete from category where pid=0/'0'/'0';

SELECT
  id
FROM
  (
    SELECT
      t1.id,
      IF (
              find_in_set(pid, @pids) > 0
            OR find_in_set(id, @pids) > 0,
              @pids := concat(@pids, ',', id),
              0
        ) AS ischild
    FROM
      (
        SELECT
          id,
          pid
        FROM
          category1
      ) t1,
      (SELECT @pids := 3) t2
  ) t3
WHERE
    ischild != 0;

# 三级菜单联查
SELECT
  *
FROM
  category
WHERE
    id IN (
    SELECT
      id
    FROM
      (
        SELECT
          t1.id,
          IF (
                  find_in_set(pid, @pids) > 0
                OR find_in_set(id, @pids) > 0,
                  @pids := concat(@pids, ',', id),
                  0
            ) AS ischild
        FROM
          (SELECT id, pid FROM category) t1,
          (SELECT @pids := 3) t2
      ) t3
    WHERE
        ischild != 0
  );


SELECT id FROM
  (SELECT t1.id,
          IF (
                  find_in_set(pid, @pids) > 0
                OR find_in_set(id, @pids) > 0,
                  @pids := concat(@pids, ',', id),
                  0
            ) AS ischild
   FROM
     (
       SELECT
         id,
         pid
       FROM
         category1
     ) t1,
     (SELECT @pids := 3) t2
  ) t3
WHERE
    ischild != 0;


SELECT id,classname FROM
  (SELECT t1.id,t1.classname,
          IF (
                  find_in_set(pid, @pids) > 0
                OR find_in_set(id, @pids) > 0,
                  @pids := concat(@pids, ',', id),
                  0
            ) AS ischild
   FROM
     (
       SELECT
         id,
         pid,classname
       FROM
         category
     ) t1,
     (SELECT @pids := 3) t2
  ) t3
WHERE
    ischild != 0;

truncate article;
create table category1 like category;
insert into category1 (id, classname, orderby, pid)  select id,classname,orderby,pid from category;
