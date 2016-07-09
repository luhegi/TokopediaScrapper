<?php
error_reporting(-1);
/**
 * @Author Muhammad imamul Azmi <imamul.azmi@hotmail.com>
 */
class Get_product
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
	
	function __construct($url)
	{
		$this->url = $url;
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
	
	public function generate($format = 'json') 
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
$data = new Get_product('http://wap.tokopedia.com/sonyacell34/new-oppo-r7s-16gb-gransi-1-tahun-pin5fcb22a2');
echo $data->generate();