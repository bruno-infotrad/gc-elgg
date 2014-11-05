<?php

/*

CometChat
Copyright (c) 2012 Inscripts

CometChat ('the Software') is a copyrighted work of authorship. Inscripts 
retains ownership of the Software and any copies of it, regardless of the 
form in which the copies may exist. This license is not a sale of the 
original Software or any copies.

By installing and using CometChat on your server, you agree to the following
terms and conditions. Such agreement is either on your own behalf or on behalf
of any corporate entity which employs you or which you represent
('Corporate Licensee'). In this Agreement, 'you' includes both the reader
and any Corporate Licensee and 'Inscripts' means Inscripts (I) Private Limited:

CometChat license grants you the right to run one instance (a single installation)
of the Software on one web server and one web site for each license purchased.
Each license may power one instance of the Software on one domain. For each 
installed instance of the Software, a separate license is required. 
The Software is licensed only to you. You may not rent, lease, sublicense, sell,
assign, pledge, transfer or otherwise dispose of the Software in any form, on
a temporary or permanent basis, without the prior written consent of Inscripts. 

The license is effective until terminated. You may terminate it
at any time by uninstalling the Software and destroying any copies in any form. 

The Software source code may be altered (at your risk) 

All Software copyright notices within the scripts must remain unchanged (and visible). 

The Software may not be used for anything that would represent or is associated
with an Intellectual Property violation, including, but not limited to, 
engaging in any activity that infringes or misappropriates the intellectual property
rights of others, including copyrights, trademarks, service marks, trade secrets, 
software piracy, and patents held by individuals, corporations, or other entities. 

If any of the terms of this Agreement are violated, Inscripts reserves the right 
to revoke the Software license at any time. 

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";

if (file_exists(dirname(__FILE__).'/data.php')) {

	include dirname(__FILE__).'/data.php';
	$games = unserialize(trim($games));

} else {

	$url = "http://www.tictacti.com/feed/games.feed?publisherId=k4xo3eH3wNI%3d&format=json";

	$ch = curl_init();
	$target = $url;
	curl_setopt($ch, CURLOPT_URL, $target);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$data = curl_exec($ch);
	$data = json_decode($data);

	$games = array();

	foreach ($data->feed->games as $game) {
		$title = $game->title;
		$thumbnail = $game->summary->div->a->img->{'@src'};
		$category = 'featured';

		if (!empty($game->category)) {
			foreach ($game->category as $cat) {
				if (!empty($cat) && is_string($cat)) { $category = $cat; break;}
				if (!empty($cat->{'@term'}) && is_string($cat->{'@term'})) { $category = $cat->{'@term'}; break;}
			}
		}
		
		foreach ($game->summary->div->{'dl'}->dd as $info) {
			if (!empty($info->{'@class'}) && $info->{'@class'} == 'width') { $width = $info->{'#text'}; }
			if (!empty($info->{'@class'}) && $info->{'@class'} == 'height') { $height = $info->{'#text'}; }
			if (!empty($info->code) && !empty($info->code->{'#text'}) && $info->code->{'@class'} == 'iframe') { $code = $info->code->{'#text'}; }
		}

		if (!empty($title) && !empty($thumbnail) && !empty($category) && !empty($width) && !empty($height) && !empty($code)) {
			if (!empty($category) && empty($games[$category])) {
				$games[$category] = array();
			}
			
			array_push($games[$category], array("title" => $title, "thumbnail" => $thumbnail, "width" => $width, "height" => $height, "code" => $code));

		}
	}

	$store = serialize($games);
	$fh = fopen(dirname(__FILE__).'/data.php', 'w');
	fwrite($fh, '<?php'."\r\n".'$games = <<<EOD'."\r\n".$store."\r\n".'EOD;'."\r\n".'?>');
	fclose($fh);
}


if (!empty($_GET['get']) && $_GET['get'] == 'categories') {
	$categories = array();
	foreach ($games as $category => $game) {
		$categories[] = array('display_name' => ucwords($category), 'name' => $category, 'num_games' => 0);
	}
	$data = array('categories' => $categories);
	echo json_encode($data);
} else if (!empty($_GET['get'])) {
	$category = $_GET['get'];
	$games = $games[$category];
	
	$g = array();

	foreach ($games as $game) {
		$g[] = array('display_name' => $game['title'], 'thumb_100x100' => $game['thumbnail'], 'width' => $game['width'], 'height' => $game['height'], 'embed_code' => $game['code'], 'description' => '');
	}

	$data = array('games' => $g);
	echo json_encode($data);
}