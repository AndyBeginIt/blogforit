blogforit
=========

这个项目是指sinaapp上面使用Thinkphp开发的，网站地址：http://blogforit.sinaapp.com/ 

NOTICE
------------

* Memcache
查看博客文章(View/index)的时候，第一次查看我们从数据库取出数据，第二次我们就从memcache里面取数据。
$ sudo apt-get install php5-memcache  memcached . (具体配置文件请查看README_CONFIG)
将CacheMemcache类放在了ORG.Util目录下面方便导入。
<pre>
[php.ini中memcache配置]
memcache.allow_failover = on
memcache.max_failover_attempts = 20
memcache.chunk_size = 8192
memcache.default_port = 11211
memcache.hash_strategy = 'standard'
memcache.hash_function = 'crc32'
</pre>

* Sphinx , Coreseek
网站搜索采用的是Coreseek中文检索引擎进行分词搜索。注意要首先生成索引，然后进行服务端开启。
(注意，这里我还要使用一个Tags标签的搜索，所以可以按照sphinx.conf从新写个tags.conf,
然后select id,tags from mb_blog .特别要重新设置端口号(9313)以及相关配置(名字什么的都要重新设置如：src2 , test2))
$ /usr/local/coreseek/bin/indexer -c /usr/local/coreseek/etc/sphinx.conf --all
$ /usr/local/coreseek/bin/searchd -c /usr/local/coreseek/etc/sphinx.conf
<pre>
[spinx.conf配置文件]
  source src1
	{
	        type                                    = mysql
	
	        sql_host                                = localhost
	        sql_user                                = root
	        sql_pass                                = xiaozhe
	        sql_db                                  = myblog
	        sql_port                                = 3306  # optional, default is 3306
	        sql_query_pre                           = SET NAMES utf8
	        sql_query                               = \
	                SELECT id, title \
	                FROM mb_blog
	
	}
	
	
	index test1
	{
	        source                                  = src1
	        path                                    = /usr/local/coreseek/var/data/test1
	        docinfo                        	        = extern
	        charset_type                    		= zh_cn.utf-8
	        charset_dictpath               		 	= /usr/local/mmseg3/data
	}
	
	
	indexer
	{
	        mem_limit                               = 32M
	}
	
	
	searchd
	{
	        port                            = 9312
	        log                             = /usr/local/coreseek/var/log/searchd.log
	        query_log                       = /usr/local/coreseek/var/log/query.log
	        read_timeout                    = 5
	        max_children                    = 30
	        pid_file                        = /usr/local/coreseek/var/log/searchd.pid
	        max_matches                     = 1000
	        seamless_rotate                 = 1
	        preopen_indexes                 = 0
	        unlink_old                      = 1
	}
</pre>


<p>
3.使用了ajaxfileupload.js
</p>

<p>
4.在站内搜索，我使用了Google Auto Complete。实现代码文件在/Tpl/Home/Default/Public/top .
</p>

<p>
5.结合ckeditor和代码高亮插件SyntaxHighlighter作为editor .
</p>

<pre>
附录：一些配置启动项
$ sudo vim /etc/rc.local

#[set IP]
$ ifconfig eth0:1 192.168.0.130 broadcast 192.168.0.255 netmask 255.255.255.0 up
$ route add -host 192.168.0.130 dev eth0:1

#[start memcache]
$ /usr/bin/memcached -m 64 -p 11211 -u memcache -l 127.0.0.1

#[start svn]
$ sudo svnserve -d -r /home/xiaozhe/svn

#[start sphinx]
$ sudo /usr/local/coreseek/bin/indexer -c /usr/local/coreseek/etc/sphinx.conf --all
$ sudo /usr/local/coreseek/bin/searchd -c /usr/local/coreseek/etc/sphinx.conf 

</pre>
