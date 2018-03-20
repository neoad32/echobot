<?php
$access_token='Bdju8/1ItyjVDSflWcZSKYkbFZOfVkwikhDr4DDe7syuUPBqjuHeZXFCqTt4Pp08o7NkMaPPCYddCHIQKIjQTb8nk5TtfpoAVcUFLVCVygypUEK0R7LD82k8QiIyw7+H53JuQ8k1X4Bm1cDhhKQ/XgdB04t89/1O/w1cDnyilFU=';	//ใส่ access token ของเรา

$content = file_get_contents('php://input');	//รับค่าจากล่องข้อความใน line
$events = json_decode($content, true);			//เปลี่ยน json ที่ line ส่งมา ให้เป็น array
if (!is_null($events['events'])) {				//ตรวจสอบว่ามีข้อมูลส่งมาหรือไม่
	foreach ($events['events'] as $event) {	
		$replyToken=$event['replyToken'];		//Token สำหรับส่งข้อความกลับ
		$text=$event['message']['text'];		//รับค่าข้อความที่ส่งเข้ามาในตัวแปร text
		
		//สร้างข้อความตอบกลับ
		$messages = [
			[
			'type' => 'text',
			'text' => $text
			]
		];
		$url = 'https://api.line.me/v2/bot/message/reply';		//url สำหรับตอบกลับ
		$data = [
			'replyToken' => $replyToken,				//replyToken ใส่ตรงนี้
			'messages' => $messages,
		];
		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
														//headers สำหรับตอบกลับ
		$ch = curl_init($url);							//เริ่ม curl 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");//ปรับเป็นแบบ post
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);	//ใส่ข้อความที่จะส่ง
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	//ส่ง header
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	
		curl_exec($ch);									//ส่งไปให้ไลน์ตอบกลับ
	}
}
echo 'OK';
