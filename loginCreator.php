<?php
   class loginCreator
   {
        private $curlinit;
        private $pharmaCode;
        private $pharmaUserName;
        private $pharmaUserPass;
        function regexExtract($text, $regex, $nthValue = 0)
        {
            if (preg_match($regex, $text, $regs)) {
            $result = $regs[$nthValue];
            }
            else 
            {
                $result = "";
            }
            return $result;
       }
       function __construct($userName, $userPass, $pharmacyCode)
       {
        
            $this -> pharmaCode = $pharmacyCode;
            $this -> pharmaUserName = $userName;
            $this -> pharmaUserName = $userPass;
            $cookiejar = realpath('cookies.txt');
            $this -> curlinit = curl_init('http://webdepo.selcukecza.com.tr/Login.aspx');
            curl_setopt($this -> curlinit, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this -> curlinit, CURLOPT_HEADER, false);
            curl_setopt($this -> curlinit, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this -> curlinit, CURLOPT_COOKIEJAR, $cookiejar);
            $loginInit = curl_exec($this -> curlinit);
            $viewstate = self::regexExtract($loginInit, '/__VIEWSTATE\" value=\"(.*)\"/i', 1);
            $viewstategen = self::regexExtract($loginInit, '/__VIEWSTATEGENERATOR\" value=\"(.*)\"/i', 1);
            
            $params = [
                '__LASTFOCUS'          => '',
                '__EVENTTARGET'        => '',
                '__EVENTARGUMENT'      => '',
                '__VIEWSTATE'          => $viewstate,
                '__VIEWSTATEGENERATOR' => $viewstategen,
                'txtKullaniciAdi'      => $userName,
                'txtSifre'             => $userPass,
                'txtEczaneKodu'        => $pharmacyCode
            ];
            $postdata = http_build_query($params) . '&btnGiris=Giri%C5%9F';
            curl_setopt($this -> curlinit, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this -> curlinit, CURLOPT_POST, true);
            curl_setopt($this -> curlinit, CURLOPT_POSTFIELDS, $postdata);
            $loginResult = curl_exec($this -> curlinit);            
            curl_setopt($this -> curlinit, CURLOPT_POST, false);
            curl_setopt($this -> curlinit, CURLOPT_URL, 'http://webdepo.selcukecza.com.tr/');
            curl_setopt($this -> curlinit, CURLOPT_COOKIEJAR, $cookiejar);
            $marks = curl_exec($this -> curlinit);
       }
       function getMedListWithTips($billtips)
       {
            curl_setopt($this -> curlinit, CURLOPT_URL, 'http://webdepo.selcukecza.com.tr/Siparis/hizlisiparis.aspx');
            $marks = curl_exec($this -> curlinit);
            $postdata = "action=GetUrunler&baslangicSayfasi=0&isInculude=false&isStoktakiler=false&marka=&s=&sayfaMaxRowAdet=20&searchText=".$billtips."&siralama=ilacASC&topRowNum=0";
            curl_setopt($this -> curlinit, CURLOPT_URL, 'http://webdepo.selcukecza.com.tr/Siparis/hizlisiparis-ajax.aspx');
            curl_setopt($this -> curlinit, CURLOPT_POST, true);
            curl_setopt($this -> curlinit, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($this -> curlinit, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($this -> curlinit);
            curl_setopt($this -> curlinit, CURLOPT_POST, false);
            return $result;
       }
       function getMedDetails($medName, $medCode, $medType)
       {
            $postdata = "ILACTIP=".$medType."&action=GetIlacDetay&esdeger=&isEsdeger=false&kampKodu=&kod=".$medCode."&tip=null";
            curl_setopt($this -> curlinit, CURLOPT_URL, 'http://webdepo.selcukecza.com.tr/Ilac/IlacGetir-ajax.aspx');
            curl_setopt($this -> curlinit, CURLOPT_REFERER, 'http://webdepo.selcukecza.com.tr/Siparis/hizlisiparis.aspx'); 
            curl_setopt($this -> curlinit, CURLOPT_POST, true);
            curl_setopt($this -> curlinit, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($this -> curlinit, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($this -> curlinit);
            return $result;
       }
       function destroySession()
       {
           curl_close($this -> curlinit);
       }
   }
?>
