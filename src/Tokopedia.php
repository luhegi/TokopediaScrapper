<?php
/**
 * @Author Muhammad imamul Azmi <imamul.azmi@hotmail.com>
 */
class Tokopedia {
	/**
	 * @var String
	 */
	private $url;
	/**
	 * @var String
	 */
	private $html;
	/**
	 * @var string
	 */
	private $seller_username;
	/**
	 * @var string
	 */
	private $the_product_sufix;
	/**
	 * @param $url
	 */
	function __construct($url) {
		// sanitize set
		$this->sanitizeUrl($url);
	}

	/**
	 * Sanitize URLs
	 *
	 * @access private
	 * @param string $url url of product
	 * @return string
	 * @throw Exception
	 */
	private function sanitizeUrl($url) {
		if (!is_string($url)) {
			throw new Exception("Invalid Url", E_USER_ERROR);
		}
		$url = preg_replace('/^(https?\:)?\/\/(www\.)?tokopedia\.com/i', 'https://www.tokopedia.com', $url);
		if (strpos($url, 'https://www.tokopedia.com') !== 0) {
			throw new Exception("Invalid Url Protocol Given", E_USER_ERROR);
		}
		preg_match('/https\:\/\/www\.tokopedia\.com\/([a-zA-Z0-9\_]{3,20})\/(.+)/', $url, $match);
		if (empty($match[1]) || empty($match[2]) || strlen($match[2]) < 3) {
			throw new Exception("Invalid Url Product.", E_USER_ERROR);
		}
		$this->seller_username = trim(strtolower($match[1]));
		if (strpos($match[2])) {
			$sufix    = explode('?', $match[2]);
			$sufix[0] = rtrim($sufix[0], '/');
			$match[2] = implode('?', $sufix);
		}
		$this->the_product_sufix = $match[2];
		$this->url               = "https://www.tokopedia.com/{$this->seller_username}/{$this->the_product_sufix}";
		return $this->url;
	}
	/**
	 * @access private
	 * @param $var
	 * @return mixed
	 */
	private function valid_result($var) {
		if ($var !== NULL && isset($var)) {
			return $var;
		} else {
			return false;
		}
	}
	/**
	 * @access private
	 */
	private function get_info() {
		$url  = $this->url . '/info';
		$data = $this->get_curl($url, TRUE);
		$ret  = '';

		// Product Title
		preg_match('/(<h1 class="product-title green"><a href="(.*)" itemprop="name" content="(.*)">)(.*<\/a><\/h1><div)/', $data, $title);
		$ret['title'] = $this->valid_result($title[2]);

		// Product Descriptions
		preg_match('/(<p itemprop="description" class="mt-20">)(.*)(<\/p><\/div><\/div><\/div><div)/', $data, $title);
		$ret['description'] = $this->valid_result($desc[2]);

		// Product Price
		preg_match('/(<span class="bold" itemprop="price">)(.*)(<\/span><\/div><small id="pcashback"))/', $data, $price);
		$ret['price'] = $this->valid_result($price[2]);

		// Product Weight
		preg_match('/((<\/i>Berat<\/dt><dd class="pull-left m-0">)(.*)(<dt class="pull-left"><i class="icon-shopping)/', $data, $weight);
		$ret['weight'] = $this->valid_result($weight[2]);

		// preg_match('/(<p><small>Kategori: )(.*)(<\/small><\/p>)/', $data, $category);
		// $ret['category'] = $this->valid_result($category[2]);

		preg_match('/(Kondisi<\/dt><dd class="pull-left m-0 border-none">)(.*)(<\/dd><link href=)/', $data, $conditions);
		$ret['conditions'] = $this->valid_result($conditions[2]);

		return $ret;
	}

	/**
	 * @return mixed
	 */
	private function get_image() {
		$url  = $this->url . '/gallery';
		$data = $this->get_curl($url, TRUE);
		preg_match_all('/("prod-details" src=")(.*)(\"\/><\/li>)/', $data, $image);
		$image     = [$image[2]];
		$image_new = '';
		foreach ($image as $img) {
			$image_new = preg_replace('/(.*)(cache\/300\/)(.*)/', '$1$3', $img);

		}
		return $image_new;

	}

	public function generate() {
		$data['info']  = $this->get_info();
		$data['image'] = $this->get_image();

		return json_encode($data, JSON_HEX_AMP);
	}

	/**
	 * @param $url
	 * @param $diff
	 * @return mixed
	 */
	private function get_curl($url, $diff = false) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, "https://www.google.co.id/");
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$output = curl_exec($ch);
		curl_close($ch);
		$this->html = $output;
		return $output;

	}
}