<?php
/*
	Diablo Immortal Events Reminder for Telegram


	Create a telegram bot. Get his token ($token)
	Create chats in telegram. Get their id ($chat_id)
	Add bot in each chat as admin
	Just run this script each minute on cron

	Создайте бота в телеграм. Получите его токен ($token)
	Создайте чаты в телеграм. Получите их id ($chat_id)
	Добавьте бота в каждый чат как админа
	Настройте запуск скрипта в cron каждую минуту
*/

date_default_timezone_set('UTC');

$token = "1111111111:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";


$chatDB = [
	1 => [
		"chat_id" => -1001111111111,
		"server" => "North America UTC-7",
		"time_offset" => -7,
	],
	2 => [
		"chat_id" => -1001111111111,
		"server" => "North America UTC-4",
		"time_offset" => -4,
	],
	3 => [
		"chat_id" => -1001111111111,
		"server" => "South America UTC-3",
		"time_offset" => -3,
	],
	4 => [
		"chat_id" => -1001111111111,
		"server" => "Europe UTC+2",
		"time_offset" => 2,
	],
	5 => [
		"chat_id" => -1001111111111,
		"server" => "South-East Asia UTC+8",
		"time_offset" => 8,
	],
	6 => [
		"chat_id" => -1001111111111,
		"server" => "East Asia UTC+9",
		"time_offset" => 9,
	],
	7 => [
		"chat_id" => -1001111111111,
		"server" => "Oceania UTC+10",
		"time_offset" => 10,
	],
];

$events = [
	1 => [  //  Monday
		"08:00" => "Battleground",
		"12:00" => ["Battleground", "Demon Gates", "Raid the Vault"],
		"18:00" => ["Assembly", "Battleground"],
		"19:00" => "Raid the Vault",
		"20:30" => "Demon Gates",
		"22:00" => ["Demon Gates", "Battleground"]
	],
	2 => [  //  Tuesday
		"08:00" => "Battleground",
		"12:00" => ["Battleground", "Haunted Carriage", "Raid the Vault"],
		"18:00" => ["Assembly", "Battleground"],
		"19:00" => "Raid the Vault",
		"20:30" => "Haunted Carriage",
		"21:30" => "Ancient Arena",
		"22:00" => ["Haunted Carriage", "Battleground"]
	],
	3 => [  //  Wednesday
		"08:00" => "Battleground",
		"12:00" => ["Battleground", "Ancient Nightmare", "Raid the Vault"],
		"18:00" => ["Assembly", "Battleground"],
		"19:00" => "Raid the Vault",
		"20:30" => "Ancient Nightmare",
		"22:00" => ["Ancient Nightmare", "Battleground"]
	],
	4 => [  //  Thursday
		"08:00" => "Battleground",
		"12:00" => ["Battleground", "Demon Gates", "Raid the Vault"],
		"18:00" => ["Assembly", "Battleground"],
		"19:00" => "Raid the Vault",
		"20:30" => "Demon Gates",
		"21:30" => "Ancient Arena",
		"22:00" => ["Demon Gates", "Battleground"]
	],
	5 => [  //  Friday
		"08:00" => "Battleground",
		"12:00" => ["Battleground", "Ancient Nightmare", "Raid the Vault"],
		"18:00" => ["Assembly", "Battleground"],
		"19:00" => "Raid the Vault",
		"20:30" => "Ancient Nightmare",
		"22:00" => ["Ancient Nightmare", "Battleground"]
	],
	6 => [  //  Saturday
		"08:00" => "Battleground",
		"12:00" => ["Battleground", "Haunted Carriage", "Raid the Vault"],
		"18:00" => ["Assembly", "Battleground"],
		"19:00" => "Raid the Vault",
		"20:30" => "Haunted Carriage",
		"21:30" => "Ancient Arena",
		"22:00" => ["Haunted Carriage", "Battleground"]
	],
	7 => [  //  Sunday
		"08:00" => "Battleground",
		"12:00" => ["Battleground", "Demon Gates", "Raid the Vault"],
		"18:00" => "Battleground",
		"19:00" => "Raid the Vault",
		"20:30" => "Demon Gates",
		"21:30" => "Ancient Arena",
		"22:00" => ["Demon Gates", "Battleground"]
	]
];


foreach ($chatDB as $key => $value) {

	$chatId = $value['chat_id'];
//	$server_name = $value['server'] ;
	$time_offset = $value['time_offset'];

	$modifier = 3600 * 0 + 0;   /******* Modifier For Testing ********/

	$scriptRunTimeOffset = 5;
	$serverTimeInSecond = time() - $scriptRunTimeOffset + $time_offset * 60 * 60 + $modifier;

	$messageEvents = '';
	$messageUpcomingEvents = '';
	$messageUpcomingEventsTest = '';

	$dayOfWeek = date("N");	//  Day of the week

	foreach ($events[$dayOfWeek] as $key => $value) {

		is_array($value) ? $eventName = implode(", ", $value) : $eventName = $value;
		$eventTimeInHours = $key;
		$eventTimeInSecond = strtotime($eventTimeInHours);

		$countdownTimeInSeconds = $eventTimeInSecond - $serverTimeInSecond;	// Оставшееся время в секундах до события $events
		$countdownTimeInMinutes = gmdate("i", $countdownTimeInSeconds);		// Оставшееся время в минутах до события $events
		/*
			Осталось 15 минут до события $events
			15 minutes left until the event $events
		*/
		if ($countdownTimeInSeconds > 870 && $countdownTimeInSeconds < 930 ) {
			$messageEvents .= "<b>" . $eventName . "</b> starts in <b>" . $countdownTimeInMinutes . " min</b>\n";
		}
		/*
			Осталось 5 минут до события $events
			5 minutes left until the event $events
		*/
		if ($countdownTimeInSeconds > 270 && $countdownTimeInSeconds < 330  ) {
			$messageEvents .= "<b>" . $eventName . "</b> starts in <b>" . $countdownTimeInMinutes . " min</b>\n";
		}
		/*
			Если время по серверу 07:45 отослать сводное сообщение со всеми предстоящими ивентами на этот день
			If server time 07:45 send message with all today's upcoming events
		*/
		if (date("H:i", $serverTimeInSecond + $scriptRunTimeOffset) == '07:45') {
			$messageUpcomingEvents .= "<b>" . $eventTimeInHours . "</b> - <b>" . $eventName . "</b>\n";
		}
	}


	if ($messageUpcomingEvents) {
		$messageUpcomingEvents = "Today's upcoming events (server time):\n" . $messageUpcomingEvents;
		sendMessage($chatId, $token, $messageUpcomingEvents);
	}

	if ($messageEvents) {
		sendMessage($chatId, $token, $messageEvents);
	}
}


function sendMessage($chatId, $token, $message) {
	$url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatId . "&parse_mode=html&text=" . urlencode($message);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
