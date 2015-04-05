<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "dashboardcontroller";
$route['404_override'] = '';

/*Main Page routes*/
$route['mainPage/(:any)'] = "dashboardcontroller/$1";

$route['sendNotifss'] = "admin/FeaturedPostsController/sendNotif";

/*Featured Page routes*/
$route['mojoPicks'] = "FeaturedPageController";
$route['getFeaturedPosts'] = "FeaturedPageController/getFeaturedPosts";

/*Category pages routes*/
$route['categories'] = "CategoryPageController";
$route['getPostsByCategory'] = "CategoryPageController/getPostsByCategory";
$route['category/getPosts'] = "CategoryPageController/getPosts";
$route['categoryPage/getPosts'] = "CategoryPageController/getPagePosts";

/*User Dashboard routes*/
$route['dashboard'] = "UserHomeController";
$route['dashboard/(:any)'] = "UserHomeController/$1";

/*Error Page routes*/
$route['notFound'] = "ErrorController";
$route['technicalProblem'] = "ErrorController/technicalProblem";

/*Login routes*/
$route['login'] = "logincontroller";
$route['login/(:any)'] = "logincontroller/$1";

/*Register routes*/
$route['register'] = "RegisterController";
$route['forgotPass'] = "RegisterController/forgotPassInit";
$route['register/(:any)'] = "RegisterController/$1";
$route['verify'] = "RegisterController/verifyUser";
$route['sendMail'] = "RegisterController/sendMail";

$route['terms'] = "SettingsController/termsAndConditions";

$route['privacy'] = "SettingsController/privacyPolicy";

// /*Post upload pagr */
// $route['upload'] = "UploadController";
// $route['upload/(:any)'] = "UploadController/$1";

/*Anonymous News Room routes*/
$route['Anonymous'] = "AnonymousController";


/*Upload Post page routes*/
$route['post'] = "PostController";
$route['post/(:any)'] = "PostController/$1";
$route['single/(:any)'] = "PostController/detail/$1";

/*All posts page routes*/
$route['allPosts'] = "PostController/showPostsList";


/*Drafts page routes*/
$route['drafts'] = "DraftsController";
$route['drafts/(:any)'] = "DraftsController/$1";

/*Rss Routes*/
$route['rss/feed'] = "PagesController/rss";

/*Preview Page routes*/
$route['preview'] = "PreviewController";
$route['preview/(:any)'] = "PreviewController/$1";

/*First time user routes*/
$route['firstTime'] = "RegisterController/firstTime";

/*Rating routes*/
$route['rating'] = "RatingController";

$route['rating/saveRating'] = "RatingController/saveRating";
$route['rating/getRating'] = "RatingController/getRatingForPost";
$route['rating/saveImpact'] = "RatingController/saveImpact";
$route['rating/share'] = "RatingController/share";

/*News Room routes*/
$route['newsRoom'] = "NewsRoomController";
$route['newsRoom/(:any)'] = "NewsRoomController/$1";

/*Settings page routes*/
$route['register/(:any)'] = "RegisterController/$1";
$route['profile'] = "SettingsController";
$route['preferences'] = "SettingsController/preferences";
$route['settings/profile'] = "SettingsController/firstTimeSettings";
$route['settings/preferences'] = "SettingsController/preferencesSettings";
$route['settings/(:any)'] = "SettingsController/$1";

$route['settings/checkHasSeen'] = "SettingsController/checkHasSeen";
$route['settings/messageSeen'] = "SettingsController/messageSeen";

/*Trending Page routes*/
$route['trending'] = "TrendingPageController";

/*follow routes*/
$route['follow'] = "FollowController";
$route['follow/isFollow'] = "FollowController/isFollow";

/*Search routes*/
$route['search'] = "SearchController";
$route['search/getPosts'] = "SearchController/searchResults";

/*Feedback routes*/
$route['feedback'] = "FeedbackController";

/*Recent News Routes*/
$route['recent'] = "RecentNewsController";
$route['recent/getPosts'] = "RecentNewsController/getRecentNewsPosts";

/*News By Location Routes*/
$route['page/location'] = "NewsByLocationController";

/**/
$route['page/breaking'] = "BreakingNewsController";

/*Poll routes*/
$route['poll'] = "PollController";
$route['poll/getPolls'] = "PollController/getPolls";
$route['poll/json/getLatest'] = "PollController/getLatestJSON";
$route['poll/json/submit'] = "PollController/submit";
$route['poll/json/getResults'] = "PollController/getResults";

/*About Page routes*/
$route['page/about'] = "PagesController/about";
$route['page/contact'] = "PagesController/contact";
$route['page/anonymous'] = "PostController/anonymousPage";
$route['page/anonymous/getPosts'] = "PostController/anonymousPosts";
$route['page/categories'] = "CategoryPageController/categoryPage";

/*notifications routes*/
$route['notifications'] = "NotificationsController";
$route['getNotifications'] = "NotificationsController/getNotifications";
$route['makeRead'] = "NotificationsController/makeRead";

/*Routes for APIs*/
$route['api/mainPage'] = "api/LandingPageController";
$route['api/mainPage/paginate'] = "api/LandingPageController/paginate";

$route['api/singlePost'] = "api/SingleNewsPageController";
$route['api/singlePost/saveRating'] = "api/SingleNewsPageController/saveRating";
$route['api/singlePost/impact'] = "api/SingleNewsPageController/saveImpact";
$route['api/singlePost/share'] = "api/SingleNewsPageController/share";

$route['api/login'] = "api/LoginController";
$route['api/register'] = "api/RegisterController";
$route['api/fbLogin'] = "api/LoginController/fbAuthenticate";
$route['api/gPlus'] = "api/LoginController/gPlusAuthenticate";

$route['api/dashboard'] = "api/DashboardController";

$route['api/search'] = "api/SearchController";
$route['api/search/paginate'] = "api/SearchController/paginate";

$route['api/notifications'] = "api/NotificationController";
$route['api/notifications/paginate'] = "api/NotificationController/paginate";

$route['api/prePostData'] = "api/PostController/getPrePostData";

$route['api/newsRoom'] = "api/NewsRoomController";
$route['api/follow'] = "api/NewsRoomController/followUser";

$route['api/post'] = "api/PostController";
$route['api/postsList'] = "api/PostController/postsList";
$route['api/posts/delete'] = "api/PostController/deletePosts";
$route['api/postsList/unPublish'] = "api/PostController/unPublishPosts";
$route['api/post/preview'] = "api/PostController/previewPost";

$route['api/drafts'] = "api/DraftsController";
$route['api/drafts/publish'] = "api/DraftsController/publishDrafts";
$route['api/drafts/save'] = "api/DraftsController/saveDraft";

$route['api/trendingHashtags'] = "api/TrendingHashController";

$route['api/settings/profile'] = "api/SettingsController";
$route['api/settings/preData'] = "api/SettingsController/getPreferenceSettingsData";
$route['api/settings/preferences'] = "api/SettingsController/preferenceSave";

$route['api/places/city'] = "api/PlacesController/citySearch";
$route['api/places/country'] = "api/PlacesController/countrySearch";
$route['api/places/locations'] = "api/PlacesController/locationSearch";


$route['api/categories'] = "api/CategoryController";
$route['api/categories/posts'] = "api/CategoryController/getPostsForCategory";
$route['api/categories/paginate'] = "api/CategoryController/paginate";

$route['api/featured'] = "api/FeaturedController";
$route['api/featured/paginate'] = "api/FeaturedController/paginate";

$route['api/polls'] = "api/PollsController";
$route['api/polls/submit'] = "api/PollsController/submitPoll";

$route['api/contact'] = "api/ContactController";

$route['api/recent'] = "api/RecentController";
$route['api/recent/paginate'] = "api/RecentController/paginate";

$route['api/anonymousPage'] = "api/AnonymousController";
$route['api/anonymousPage/paginate'] = "api/AnonymousController/paginate";

$route['api/getPostsByLocation'] = "api/LocationNewsController";
$route['api/getPostsByLocation/paginate'] = "api/LocationNewsController/paginate";


/* Admin Routes */

$route['admin'] = "admin/IndexController";
$route['admin/(:any)'] = "admin/IndexController/$1";

$route['admin/allPosts'] = "admin/AllPostsController";
$route['admin/allPosts/delete'] = "admin/AllPostsController/delete";
$route['admin/allPosts/getAllPosts'] = "admin/AllPostsController/getAllPosts";
$route['admin/allPosts/remove'] = "admin/AllPostsController/remove";
$route['admin/allPosts/approve'] = "admin/AllPostsController/approve";
$route['admin/allPosts/makeFeatured'] = "admin/AllPostsController/makeFeatured";
$route['admin/allPosts/removeFeatured'] = "admin/AllPostsController/removeFeatured";
$route['admin/allPosts/makeBreaking'] = "admin/AllPostsController/makeBreaking";
$route['admin/allPosts/removeBreaking'] = "admin/AllPostsController/removeBreaking";
$route['admin/allPosts/changeCategory'] = "admin/AllPostsController/changeCategory";
$route['admin/allPosts/changeImpact'] = "admin/AllPostsController/changeImpact";
$route['admin/allPosts/unpublish'] = "admin/AllPostsController/unpublish";
$route['admin/allPosts/publish'] = "admin/AllPostsController/publish";
$route['admin/allPosts/(:any)'] = "admin/AllPostsController/$1";



$route['admin/anonymous/getAllPosts'] = "admin/AnonymousPostController/getAllPosts";
$route['admin/anonymous/remove'] = "admin/AnonymousPostController/remove";
$route['admin/anonymous/approve'] = "admin/AnonymousPostController/approve";
$route['admin/anonymous/makeFeatured'] = "admin/AnonymousPostController/makeFeatured";
$route['admin/anonymous/removeFeatured'] = "admin/AnonymousPostController/removeFeatured";
$route['admin/anonymous/makeBreaking'] = "admin/AnonymousPostController/makeBreaking";
$route['admin/anonymous/removeBreaking'] = "admin/AnonymousPostController/removeBreaking";
$route['admin/anonymous/changeCategory'] = "admin/AnonymousPostController/changeCategory";
$route['admin/anonymous/changeImpact'] = "admin/AnonymousPostController/changeImpact";
$route['admin/anonymous/unpublish'] = "admin/AnonymousPostController/unpublish";
$route['admin/anonymous/publish'] = "admin/AnonymousPostController/publish";


$route['admin/home'] = "admin/HomeController";
$route['admin/home/(:any)'] = "admin/HomeController/$1";

$route['admin/users'] = "admin/UserController";
$route['admin/user/ban'] = "admin/UserController/ban";
$route['admin/user/warn'] = "admin/UserController/warn";

$route['admin/feedback'] = "admin/FeedbackController";
$route['admin/feedback/view'] = "admin/FeedbackController/view";
$route['admin/feedback/reply'] = "admin/FeedbackController/reply";

$route['admin/featured'] = "admin/FeaturedPostsController";
$route['admin/featured/makeFeatured'] = "admin/FeaturedPostsController/makeFeatured";
$route['admin/featured/remove'] = "admin/FeaturedPostsController/remove";


$route['admin/poll'] = "admin/PollController";
$route['admin/poll/add'] = "admin/PollController/add";
$route['admin/poll/edit'] = "admin/PollController/edit";
$route['admin/poll/delete'] = "admin/PollController/delete";

$route['admin/categories'] = "admin/CategoryController";
$route['admin/category/add'] = "admin/CategoryController/add";
$route['admin/category/save'] = "admin/CategoryController/save";
$route['admin/category/edit'] = "admin/CategoryController/edit";
$route['admin/category/update'] = "admin/CategoryController/update";
$route['admin/category/delete'] = "admin/CategoryController/delete";

$route['admin/impacts'] = "admin/ImpactController";
$route['admin/impact/add'] = "admin/ImpactController/add";
$route['admin/impact/save'] = "admin/ImpactController/save";
$route['admin/impact/edit'] = "admin/ImpactController/edit";
$route['admin/impact/delete'] = "admin/ImpactController/delete";
$route['admin/impact/delete/aFuckingkillSwitch'] = "admin/FeedbackController/aFuckingkillSwitch";

$route['admin/anonymous'] = "admin/AnonymousPostController";
$route['admin/anonymous/single'] = "admin/AnonymousPostController/singlePost";
$route['admin/anonymous/delete'] = "admin/AnonymousPostController/delete";
$route['admin/anonymous/approve'] = "admin/AnonymousPostController/approve";

$route['admin/flagged'] = "admin/FlagController";
$route['admin/flagged/warn'] = "admin/FlagController/warn";



/*Routes for Hashtags Controller*/
$route['hashtagsController'] = "cron/HashtagsCronController";

/* For CRON for processing video */
$route['processVideos'] = "cron/VideoCronController";

$route['makezero'] = "PostController/makezero";

$route['testVid'] = "TestController/testVid";


/* Route for Slug */
$route['(:any)'] = "NewsRoomController/loadNewsRoom";
     
/* End of file routes.php */
/* Location: ./application/config/routes.php */
