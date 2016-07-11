<?php

/**
 * Tokopedia Product Parser
 *
 * @package TokopediaScrapper
 * @version 1.0
 * @author Muhammad Imamul Azmi <imamul.azmi@hotmail.com>
 */
class TokopediaScrapper
{
    /**
     * @var String
     */
    private $url;

    /**
     * @var CURL Configuration
     */
    private $curlConfig;

    /**
     * @var CURL POST Fields
     */
    private $curlPost;

    /**
     * @var Sellers Username
     */
    private $sell_username;

    /**
     * @var Product Suffix
     */
    private $product_sufix;

    /**
     * constructor Tokopedia
     *
     * @param      string  $TokopediaUrl  URL Tokopedia Product
     */
    public function __construct($TokopediaUrl)
    {
        $this->sanitizeUrl($TokopediaUrl);
        $this->getCurl($TokopediaUrl);
    }

    /**
     * Sanitize URLs
     *
     * @access private
     * @param string $url url of product
     *
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
            throw new Exception("Invalid Tokopedia url Given", E_USER_ERROR);
        }
        preg_match('/https\:\/\/www\.tokopedia\.com\/([a-zA-Z0-9\_]{3,20})\/(.+)/', $url, $match);
        if (empty($match[1]) || empty($match[2]) || strlen($match[2]) < 3) {
            throw new Exception("Invalid Url Product.", E_USER_ERROR);
        }
        $this->sell_username = trim(strtolower($match[1]));
        // if (strpos($match[2])) {
        $sufix    = explode('?', $match[2]);
        $sufix[0] = rtrim($sufix[0], '/');
        $match[2] = implode('?', $sufix);
        // }
        $this->product_sufix = $match[2];
        $this->url           = "https://www.tokopedia.com/{$this->sell_username}/{$this->product_sufix}";
    }

    /**
     * Get Validation from Result
     *
     * @access private
     * @param $var
     *
     * @return mixed
     */
    private function valid_result($var)
    {
        if (isset($var) == false) {
            return false;
        }
        if ($var !== null) {
            return $var;
        } else {
            return false;
        }
    }

    /**
     * Get Product Details Data
     *
     * @access     private
     *
     * @return     array  Product Details Array
     * @throw      Exception
     */
    private function getProductInfo()
    {
        $data = $this->html;
        // Product Title
        if (preg_match('/<li class="active"><h2>(.*)<\/h2><\/li>/', $data, $title)) {
            $ret['title'] = $this->valid_result($title[1]);
        } else {
            $ret['title'] = false;
        }

        // Product Descriptions
        if (preg_match('/(<p itemprop="description" class="mt-20">)(.*)(<\/p><\/div><\/div><\/div><div)/', $data, $desc)) {
            $ren['description'] = $this->valid_result($desc[2]);
        } else {
            $ren['description'] = false;
        }

        // Product Price
        if (preg_match('/(<span class="bold" itemprop="price">)(.*)(<\/span><\/div><small id="pcashback")/', $data, $price)) {
            $ren['price'] = $this->valid_result($price[2]);
        } else {
            $ren['price'] = false;
        }

        // Product Weight
        if (preg_match('/(<\/i>Berat<\/dt><dd class="pull-left m-0">)(.*)(<\/dd><dt class="pull-left"><i class="icon-shopping)/', $data, $weight)) {
            $ren['weight'] = $this->valid_result($weight[2]);
        } else {
            $ren['weight'] = false;
        }

        // Product Weight

        // preg_match('/(<p><small>Kategori: )(.*)(<\/small><\/p>)/', $data, $category);
        // $ret['category'] = $this->valid_result($category[2]);

        if (preg_match('/(Kondisi<\/dt><dd class="pull-left m-0 border-none">)(.*)(<\/dd><link href=)/', $data, $conditions)) {
            $ren['condition'] = $this->valid_result($conditions[2]);
        } else {
            $ren['condition'] = false;
        }
        if (in_array(false, $ren) == true) {
            throw new Exception("Error to Parse Info Data", E_USER_WARNING);
        }
        return $ren;
    }

    /**
     * get Product Image from WAP Website ( Still on Development )
     *
     * @access     private
     *
     * @return     string  array image list
     * @throw      Exception
     */
    private function getProductImage()
    {
        $url  = "http://wap.tokopedia.com/{$this->sell_username}/{$this->product_sufix}/gallery";
        $data = $this->getCurl($url, true);
        if (preg_match_all('/("prod-details" src=")(.*)(\"\/><\/li>)/', $data, $image)) {
            $image[]   = $image[2];
            $image_new = '';
            foreach ($image as $img) {
                $image_new = preg_replace('/(.*)(cache\/300\/)(.*)/', '$1$3', $img);
            }
            return $image_new;
        } else {
            throw new Exception("Error Get Image from WAP Website", E_USER_WARNING);
        }
    }

    /**
     * Generate Result
     *
     * @access     public
     * @param      string   $get    Data result type
     * @param      boolean  $json   Data Type json / array
     *
     * @return     string  will produce json or array
     */
    public function generate($get = 'all', $json = true)
    {
        if ($get == 'info') {
            $data['info'] = $this->getProductInfo();
        } elseif ($get == 'image') {
            $data['image'] = $this->getProductImage();
        } else {
            $data['info']  = $this->getProductInfo();
            $data['image'] = $this->getProductImage();
        }
        if ($json == true) {
            return json_encode($data);
        } else {
            return $data;
        }
    }

    /**
     * the Independent Curl Class
     *
     * @access     private
     * @param      string   $url     Tokopedia Product URL
     *
     * @param      boolean  $return  Output Return
     * @return     string   return Output or Final Output Curl
     * @throw Exception
     */
    private function getCurl($url, $return = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_REFERER, "https://www.google.com/");
        curl_setopt($ch, CURLOPT_USERAGENT, "Googlebot/2.1 (+http://www.google.com/bot.html)");
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $output = curl_exec($ch);
        if (curl_error($ch) || curl_errno($ch)) {
            throw new Exception("Error Getting Data", E_USER_ERROR);
        } else {
            if ($return == true) {
                return $output;
            } else {
                $this->html = $output;
            }
        }
        curl_close($ch);

    }
}
