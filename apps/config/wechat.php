<?php

if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

/*
 * Key of wechat development
 */
$config['token'] = '';

/*
 * command expire time(seconds)
 */
$config['cmd_expire_time'] = 300;

/*
 * tpl for msg
 */
$config['textTpl'] = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";

$config['newsTpl'] = "<xml>
							 <ToUserName><![CDATA[%s]]></ToUserName>
							 <FromUserName><![CDATA[%s]]></FromUserName>
							 <CreateTime>%s</CreateTime>
							 <MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>1</ArticleCount>
							 <Articles>
							 <item>
							 <Title><![CDATA[%s]]></Title>
							 <Description><![CDATA[%s]]></Description>
							 <PicUrl><![CDATA[%s]]></PicUrl>
							 <Url><![CDATA[%s]]></Url>
							 </item>
							 </Articles>
							 <FuncFlag>0</FuncFlag>
							 </xml>";

$config['musicTpl'] = "<xml>
							 <ToUserName><![CDATA[%s]]></ToUserName>
							 <FromUserName><![CDATA[%s]]></FromUserName>
							 <CreateTime>%s</CreateTime>
							 <MsgType><![CDATA[%s]]></MsgType>
							 <Music>
							 <Title><![CDATA[%s]]></Title>
							 <Description><![CDATA[%s]]></Description>
							 <MusicUrl><![CDATA[%s]]></MusicUrl>
							 <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							 </Music>
							 <FuncFlag>0</FuncFlag>
							 </xml>";

/* End of file wechat.php */
/* Location: ./application/config/wechat.php */