<?php
/**
 * @Author Muhammad imamul Azmi <imamul.azmi@hotmail.com>
 */
class Get_product
{
	private $url;
	
	
	function __construct($url)
	{
		$this->$url = $url;
	}
	
	public function get_desc()
	{
		$link = $this->url . '/info';
		echo $this->get_curl($link);
	}
	
	private function get_curl($url) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, "https://www.google.co.id/");
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	} 
}
$data = new Get_product('http://wap.tokopedia.com/budgetgadget/car-bluetooth-music-receiver-with-handsfree');
$data->get_desc();