<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="style.css">
  <title>Hangry Tweets</title>
</head>
<body>
<?php 
  require_once('twitter-api-php-master/TwitterAPIExchange.php');

  $settings = array(
    'oauth_access_token' => "505233911-efsqJSgehtL501gkvU39DXrzL40RrIXmJmGg7JK4",
    'oauth_access_token_secret' => "RB40U6l9VJP3Qqnr5CzkbiQYEqbtccSNnc3tR5HV3trpZ",
    'consumer_key' => "Zi4n046kp7xFNH1UIFBbFG81H",
    'consumer_secret' => "ViEOc7vZiTAtV5GyGcdwA2vooPg045O10sECDlubTui7F8eg3T"
  );

  $url = "https://api.twitter.com/1.1/search/tweets.json";
  $requestMethod = "GET";

  // if($_GET['since_id']) {
  //   $getfield = '?'.$_GET['since_id'].'&q=hangry&result_type=mixed&tweet_mode=extended&truncated=false';
  // } else {
    $getfield = '?q=hangry&result_type=mixed&tweet_mode=extended&truncated=false';
  // }

  $twitter = new TwitterAPIExchange($settings);
  $string = json_decode($twitter->setGetfield($getfield)
  ->buildOauth($url, $requestMethod)
  ->performRequest(), $assoc = true);

  // print_r ($string);

  if (array_key_exists("errors", $string)) {
      echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";
      exit();
  }



  echo '<div class="container">';
  echo '<script data-ad-client="ca-pub-2755070196376377" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
  foreach ($string as $items) {
      if (is_array($items)) {
        foreach ($items as $value) {
          if(isset($value['full_text'])) {
            echo '<div class="item">';
            echo '<p>'.$value['full_text'].'</p>';

            $contentType = $value['extended_entities']['media'][0]['video_info']['variants'][0]['content_type'];
            if(isset($contentType)&&($contentType=='video/mp4')) { ?>
              <video autoplay loop muted inline>
                <source src="<?php echo $value['extended_entities']['media'][0]['video_info']['variants'][0]['url']; ?>" type="video/mp4">
              </video>
            <?php } else {
              echo '<img src="'.$value['extended_entities']['media'][0]['media_url_https'].'">';
            }
            


            echo '<div class="numbers__holder">';
              if (isset($value['favorite_count'])) {
                echo '<p class="numbers favorites"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"><path d="M12 4.248c-3.148-5.402-12-3.825-12 2.944 0 4.661 5.571 9.427 12 15.808 6.43-6.381 12-11.147 12-15.808 0-6.792-8.875-8.306-12-2.944z"/></svg>'.$value['favorite_count'].'</p>';
              }
              if (isset($value['retweet_count'])) {
                echo '<p class="numbers retweets"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"><path d="M5 10v7h10.797l1.594 2h-14.391v-9h-3l4-5 4 5h-3zm14 4v-7h-10.797l-1.594-2h14.391v9h3l-4 5-4-5h3z"/></svg>'.$value['retweet_count'].'</p>';
              }
            echo '</div>';

            echo '</div>';
          }
        }
      } 
      // if(isset($items['refresh_url'])) {
      //   echo '<a href="'.$items['refresh_url'].'" class="next_results">More Tweets</a>';
      // }
  }
  echo '</div>';


?>
</body>
</html>
