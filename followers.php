
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
//$numFollowers = 5; 

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
		


//if  ($singleFollowerFollowing )
//$singleFollowerFollowing = "0";
    
    
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

 echo "<!-- <BR>Title: $singleBlogTitle";
 echo "<BR>Name: <a target='_blank' href='$singleFollowerURL'>$singleFollowerName</a>";
 echo "<BR>URL: $singleFollowerURL";
 echo "<BR>Following: $singleFollowerFollowing";
 echo "<BR>Posts: $singleBlogPosts";
 echo "<BR>Likes: $singleBlogLikes";
 echo "<BR>Can Submit: $singleBlogCanSubmit";
 echo "<BR>Updated: $singleBlogUpdated<P>";
  echo "<BR>Description: $singleBlogDescription-->";
  
  echo "<p style=\"font-weight:normal;color:#000000;letter-spacing:1pt;word-spacing:2pt;font-size:12px;text-align:left;font-family:arial, helvetica, sans-serif;line-height:1;\">\n";
   echo "<BR>$singleBlogTitle, <a target='_blank' href='$singleFollowerURL'>$singleFollowerName</a>,$singleFollowerURL,$singleFollowerFollowing,$singleBlogPosts,$singleBlogLikes,$singleBlogCanSubmit,$singleBlogUpdated,$singleBlogDescription";
echo "</p>";

ob_flush ();
// print_r($arrSingleBlogInfo); 
 
    $x++;
} while ($x<=$numFollowers);
// Followers    -  END  Loop from 0 to numFollowers -----------------------------------
}
// Followers    -  END Paul --------------------------------------------------------------------------------------------------------------------------------------
