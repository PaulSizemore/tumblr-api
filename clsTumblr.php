<?php
// Start a session, load the library
session_start();
include_once('config.php');
include_once('dBug.php');
include_once('API/tumblroauth.php');
class ClsTumblerAPI
{
    private $strConsumerKey;
    private $strConsumerSecret;
    private $strOauthKey;
    private $strOauthSecret;
    private $objTumblrOauth;
    private $strUserBlog;

    //Constructor of class
    function __construct()
    {
        $this->strConsumerKey = CONSUMER_KEY;
        $this->strConsumerSecret = CONSUMER_SECRET;
    }

    /**
    Function getting Oauth Token
     */
    public function fnGetOauthToken()
    {
        $objTumbOuth = new TumblrOAuth($this->strConsumerKey, $this->strConsumerSecret, $_SESSION['request_token'], $_SESSION['request_token_secret']);
        // Ok, let's get an Access Token. We'll need to pass along our oauth_verifier which was given to us in the URL.
        $arrAccessToken = $objTumbOuth->getAccessToken($_REQUEST['oauth_verifier']);
        // We're done with the Request Token and Secret so let's remove those.
        $this->strOauthKey = $arrAccessToken['oauth_token'];
        $this->strOauthSecret = $arrAccessToken['oauth_token_secret'];
        unset($_SESSION['request_token']);
        unset($_SESSION['request_token_secret']);

        // Start a new instance of TumblrOAuth, overwriting the old one .
        // This time it will need our Access Token and Secret instead of our Request Token and Secret
        $this->objTumblrOauth = new TumblrOAuth($this->strConsumerKey, $this->strConsumerSecret, $this->strOauthKey, $this->strOauthSecret);
    }

    /** Getting user information
     */
    public function fnGetUserInformation()
    {
        //API Function For Getting User Information
        $objArrUserInfo = $this->objTumblrOauth->get('user/info');
      //   new dBug ( $objArrUserInfo );
        
        if (is_object($objArrUserInfo->response)) {
            //array of all users blogs
            $arrBLogs = $objArrUserInfo->response->user->blogs;
            foreach ($arrBLogs as $arrBl) {
                //BLog Url
                $this->strUserBlog = trim(str_replace('/', '', str_replace('http://', '', $arrBLogs[0]->url)));


            }
            //foreach
        }
        //if
    }//end function

    /**
     * Function for Getting Tumblr Profile Image
     */
    public function fnGetProfileImage()
    {
            //You can get a blog's avatar in 9 different sizes. The default size is 64x64.
        $strPhotoUrl = 'http://api.tumblr.com/v2/blog/' . $this->strUserBlog . '/avatar/64';
    }

    // Public Profile


// Followers    -  Paul -----------------------------------------------------------------------------------------------------------------------------------------
    public function fnGetFollowers()
    {
   // Followers    -  Get the total count of followers --------------------------------------
$strBlogUrl = "blog/" . $this->strUserBlog . "/followers"; // Call post method
$arrFollowers = $this->objTumblrOauth->get($strBlogUrl, $arrParam);
$numFollowers = $arrFollowers->response->total_users;
 $strPhotoUrl = 'http://api.tumblr.com/v2/blog/' . $this->strUserBlog . '/avatar/64';



echo "<style type=\"text/css\">\n";
echo ".tg  {margin-bottom:25px; border-collapse:collapse;border-spacing:0;}\n";
echo ".tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}\n";
echo ".tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}\n";
echo ".tg .tg-baqh{text-align:center;vertical-align:top}\n";
echo ".tg .tg-yw4l{vertical-align:top}\n";
echo "</style>\n";
echo "<table class=\"tg\">\n";
echo "  <tr>\n";
echo "    <th class=\"tg-baqh\"><img src=\"" . $strPhotoUrl . "\"></th>\n";
echo "    <th class=\"tg-yw4l\">".$this->strUserBlog."<P>Follower Count:".$numFollowers."</th>\n";
echo "  </tr>\n";
echo "</table>";



echo "<font style=\"font-weight:normal;color:#000000;letter-spacing:1pt;word-spacing:2pt;font-size:12px;text-align:left;font-family:arial, helvetica, sans-serif;line-height:1;\">\n";
echo "Title,Name,URL,Following,Posts,Likes,CanSubmit,Updated,Descripton";
echo "</font>";
 
// Followers    -  Loop from 0 to numFollowers --------------------------------------
// For Development, set it to 5
$numFollowers = 5; 

$x = 1; // The start point of the followers to get 
do {
    // Set the parameters for the API Call
        $arrParam = array(
            'offset' => $x,
            'limit' => 1,
            'api_key' => $this->strConsumerKey // App_key
        );
// Make the API Call
    	$arrFollowers = $this->objTumblrOauth->get($strBlogUrl, $arrParam);
// Set the variables 
		$arrSingleUser =  $arrFollowers->response->users;
		$singleFollowerName= $arrSingleUser[0]->name;
		$singleFollowerURL= $arrSingleUser[0]->url;
		$singleFollowerFollowing= $arrSingleUser[0]->following;

//    	echo "<P><pre>";print_r($arrSingleUser); // Dump the current array
//    	echo " $x  :$singleFollowerName <br>";

        $arrBlogInfoParam = array(
            'api_key' => $this->strConsumerKey // App_key
        );
$infoBlogURL = "https://api.tumblr.com/v2/blog/".$singleFollowerName.".tumblr.com/info/";
$arrBlogInfo = $this->objTumblrOauth->get($infoBlogURL, $arrBlogInfoParam);

// Set the variables 
		$arrSingleBlogInfo =  $arrBlogInfo->response->blog;
		$singleBlogTitle= $arrSingleBlogInfo->title;
									$singleBlogTitle = str_replace(",", '', $singleBlogTitle); // Removing commas returns 
		$singleBlogDescription= $arrSingleBlogInfo->description;
							$singleBlogDescription = str_replace("\r", '', $singleBlogDescription); // Removing carriage returns 
							$singleBlogDescription = str_replace(",", '', $singleBlogDescription); // Removing commas returns 
							$singleBlogDescription = strip_tags($singleBlogDescription); // remove HTML  
							$singleBlogDescription = htmlentities($singleBlogDescription); // HTML Encoding 
 		$singleBlogPosts= $arrSingleBlogInfo->posts;
		$singleBlogLikes= $arrSingleBlogInfo->likes;
		$singleBlogCanSubmit= $arrSingleBlogInfo->can_submit;
		$singleBlogUpdated= $arrSingleBlogInfo->updated;
  
  echo "<p style=\"font-weight:normal;color:#000000;letter-spacing:1pt;word-spacing:2pt;font-size:12px;text-align:left;font-family:arial, helvetica, sans-serif;line-height:1;\">\n";
   echo "<BR>$singleBlogTitle, <a target='_blank' href='$singleFollowerURL'>$singleFollowerName</a>,$singleFollowerURL,$singleFollowerFollowing,$singleBlogPosts,$singleBlogLikes,$singleBlogCanSubmit,$singleBlogUpdated,$singleBlogDescription";
echo "</p>";
    $x++;
} while ($x<=$numFollowers);
// Followers    -  END  Loop from 0 to numFollowers -----------------------------------
}
// Followers    -  END Paul --------------------------------------------------------------------------------------------------------------------------------------


// Following    -  Start Paul ------------------------------------------------------------------------------------------------------------------------------------

public function fnGetFollowing()
    {
    $strFollowingUrl = "user/following"; // Call post method
    echo "<HR>";
// For Development, set it to 5
///$numFollowers = 4945; 
$numFollowers = 1500; 

$x = 1; // The start point of the followers to get 
do {
    // Set the parameters for the API Call
        $arrParam = array(
            'offset' => $x,
            'limit' => 1,
            'api_key' => $this->strConsumerKey // App_key
        );
// Make the API Call
    	$arrFollowing = $this->objTumblrOauth->get($strFollowingUrl, $arrParam);
// Set the variables 
		$arrSingleUser =  $arrFollowing->response->blogs;
		$singleFollowerName= $arrSingleUser[0]->name;
		$singleFollowerURL= $arrSingleUser[0]->url;
		$singleFollowerTitle= $arrSingleUser[0]->title;
		$singleFollowerDescription	= $arrSingleUser[0]->description;

		$singleFollowerTitle = str_replace(",", '', $singleFollowerTitle); // Removing commas returns 
							$singleFollowerDescription = str_replace("\r", '', $singleFollowerDescription); // Removing carriage returns 
							$singleFollowerDescription = str_replace(",", '', $singleFollowerDescription); // Removing commas returns 
							$singleFollowerDescription = strip_tags($singleFollowerDescription); // remove HTML  
							$singleFollowerDescription = htmlentities($singleFollowerDescription); // HTML Encoding 
		
echo "<font style=\"font-weight:normal;color:#000000;letter-spacing:1pt;word-spacing:2pt;font-size:12px;text-align:left;font-family:arial, helvetica, sans-serif;line-height:1;\">\n";
   echo "<BR>$singleFollowerTitle, <a target='_blank' href='$singleFollowerURL'>$singleFollowerName</a>,$singleFollowerURL,$singleFollowerDescription";
   echo "</font>";
// new dBug ( $arrFollowing );
		
		
    $x++;
} while ($x<=$numFollowers);

    }


// Following    -  END Paul --------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Function User Blog Feed
     *
     * @param int $intPage
     */
    public function fnGetUserBlogFeed($intPage = 1)
    {
        $intStart = 0;
        $intLimit = 10;
        if ($intPage > 1) {
            $intStart = ($intPage - 1) * $intLimit;
        }
        $arrParam = array(
            'offset' => $intStart,
            'limit' => $intLimit,
            'api_key' => $this->strConsumerKey // App_key
        );
        //Url For Post Message
        $strBlogUrl = "blog/" . $this->strUserBlog . "/posts"; // Call post method
        $arrProfileUpdates = $this->objTumblrOauth->get($strBlogUrl, $arrParam);
        //Checking if post exist or not
        if (is_array($arrProfileUpdates->response->posts)) {
            foreach ($arrProfileUpdates->response->posts as $arrPosts) {
                //echo "<pre>";print_r($arrPosts);
            } //foreach
        }
        // if
    }

    /**   * Function for Post Message on Tumblr Blog   */
    public function fnPostMessageOnBlog($intFlag)
    {
   }

}


