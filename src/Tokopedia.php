<?php
/**
 * @Author Muhammad imamul Azmi <imamul.azmi@hotmail.com>
 */
class Tokopedia
{
	private $url;
	private $desc;
	private $title;
	private $image;
	private $price;
	private $weight;
	private $category;
	private $conditions;
	private $html;
	
	/**
	 * @var string
	 */
    private $seller_username;
	/**
	 * @var string
	 */
    private $the_product_sufix;

	function __construct($url)
	{
        // $this->sanitizeUrl($url); // sanitize set
		$this->url = $url;
	}

	/**
	 * Sanitize URLs
	 *
	 * @access private
	 * @param string $url url of product
	 * @return string
	 * @throw Exception
	 */
    private function sanitizeUrl($url)
    {
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
            $sufix = explode('?', $match[2]);
            $sufix[0] = rtrim($sufix[0], '/');
            $match[2] = implode('?', $sufix);
        }
        $this->the_product_sufix = $match[2];
        $this->url = "https://www.tokopedia.com/{$this->seller_username}/{$this->the_product_sufix}";
		return $this->url;
    }

	private function get_info()
	{
		$url = $this->url . '/info';
		$data = $this->get_curl($url, TRUE);
		preg_match('/(<b itemprop="name">)(.*)(<\/b><\/a>)/', $data, $title);
		$this->title = $title[2];
		preg_match('/(<h3>Deskripsi<\/h3><span>)(.*)(<\/span\>)/', $data, $desc);
		$this->desc = $desc[2];
		preg_match('/(<span itemprop="price">Rp.)(.*)(<\/span>)/', $data, $price);
		$this->price = $price[2];
		preg_match('/(<p><small>Berat: )(.*)(<\/small><\/p>)/', $data, $weight);
		$this->weight = $weight[2];
		preg_match('/(<p><small>Kategori: )(.*)(<\/small><\/p>)/', $data, $category);
		$this->category = $category[2];
		preg_match('/(<p><small>Kondisi: )(.*)(<\/small>)/', $data, $conditions);
		$this->conditions = $conditions[2];
	}


	private function get_image() 
	{
		$url = $this->url . '/gallery';
		$data = $this->get_curl($url, TRUE);
		preg_match_all('/("prod-details" src=")(.*)(\"\/><\/li>)/', $data, $image);
		$image = [$image[2]];
		$image_new = '';
		foreach ($image as $img) {
			$image_new = preg_replace('/(.*)(cache\/300\/)(.*)/', '$1$3', $img);
			
		}
		$this->image = $image_new;
		
	}
	
	public function generate() 
	{
		$this->get_info();
		$this->get_image();
		$data['title'] = $this->title;
		$data['descriptions'] = $this->desc;
		$data['price'] = $this->price;
		$data['weight'] = $this->weight;
		$data['category'] = $this->category;
		$data['conditions'] = $this->conditions;
		$data['image'] = $this->image;
		
		return json_encode($data , JSON_HEX_AMP);
	}
	
	private function get_curl($url, $diff = false) 
	{
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