# Code Samples #
## Check to see if Twitter is available ##
```
$t = new twitter();
$t->username='myuser';		
$t->password='mypass';
echo'<pre>';
print_r( $t->ratelimit() );
echo'</pre>';
```
**Returns**
```
stdClass Object
(
    [remaining_hits] => 30
)
```

## Get the Public Timeline ##
```
$t = new twitter();
$t->username='myuser';		
$t->password='mypass';
$t->type='xml';
echo'<pre>';
print_r( $t->publicTimeline() );
echo'</pre>';
```
**Returns (shortened for brevity)**
```
stdClass Object
(
    [0] => SimpleXMLElement Object
        (
            [created_at] => Thu May 29 05:24:38 +0000 2008
            [id] => 822268611
            [text] => GeoTwittering. http://geotwitter.net/l.aspx?g=78
            [source] => GeoTwitter.Net
            [truncated] => false
            [in_reply_to_status_id] => SimpleXMLElement Object
                (
                )

            [in_reply_to_user_id] => SimpleXMLElement Object
                (
                )

            [favorited] => false
            [user] => SimpleXMLElement Object
                (
                    [id] => 736393
                    [name] => Derik Olsson
                    [screen_name] => derik
                    [location] => Flower Mound, Texas
                    [description] => Entrepreneur in the video & web fields.
                    [profile_image_url] => http://s3.amazonaws.com/twitter_production/profile_images/53445703/hb_sm_normal.jpg
                    [url] => http://derikolsson.com
                    [protected] => false
                    [followers_count] => 12
                )

        )

    [1] => SimpleXMLElement Object
        (
            [created_at] => Thu May 29 05:24:54 +0000 2008
            [id] => 822268609
            [text] => @miki7500 まだ食べ終わってなかったのか
            [source] => web
            [truncated] => false
            [in_reply_to_status_id] => 822268441
            [in_reply_to_user_id] => 6098852
            [favorited] => false
            [user] => SimpleXMLElement Object
                (
                    [id] => 12902532
                    [name] => ALeE/ありーくっく
                    [screen_name] => ALeE78
                    [location] => Japan,hyogo
                    [description] => ♂。えいりと呼ぶ人もいます。格ゲー部、メガネ美人部、diablo部、モンハン部。モンハン日記→http://monsterhunter.g.hatena.ne.jp/alee/
                    [profile_image_url] => http://s3.amazonaws.com/twitter_production/profile_images/54304388/cook_normal.jpg
                    [url] => http://iddy.jp/profile/alee78/
                    [protected] => false
                    [followers_count] => 464
                )

        )
```