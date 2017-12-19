<?php

namespace Asciifier;

class Convert {

	public static function latin_to_ascii($string, $force = TRUE, $locale = FALSE) {

		if (!preg_match('/[^\x20-\x7E]/u', $string)) {
			return $string;
		}

		$secondary = [];
		if(stripos($locale, 'de') !== FALSE) $secondary = ['Ä'=>'Ae', 'ä'=>'ae', 'Ö'=>'Oe', 'ö'=>'oe', 'Ü'=>'UE', 'ü'=>'ue', 'ß'=>'ss',]; /* https://core.trac.wordpress.org/browser/tags/4.3.1/src/wp-includes/formatting.php#L1250 */
		if(stripos($locale, 'da') !== FALSE) $secondary = ['Ø'=>'Oe', 'ø'=>'oe', 'Å'=>'Aa', 'å'=>'aa',]; /* https://core.trac.wordpress.org/browser/tags/4.3.1/src/wp-includes/formatting.php#L1258 */
		if(stripos($locale, 'vi') !== FALSE) $secondary = ['Đ'=>'D' , 'đ'=>'d' ,]; /* https://en.wikipedia.org/wiki/D_with_stroke#Vietnamese */

		$string = str_replace(
			['À','à','Á','á','Â','â','Ã','ã','Ä','ä','Å','å','Æ','æ','Ç','ç','È','è','É','é','Ê','ê','Ë','ë','Ì','ì','Í','í',
			 'Î','î','Ï','ï','Ð','ð','Ñ','ñ','Ò','ò','Ó','ó','Ô','ô','Õ','õ','Ö','ö','Ø','ø','Ù','ù','Ú','ú','Û','û','Ü','ü',
			 'Ý','ý','Þ','þ','ß','Ÿ','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ',
			 'ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ',
			 'i','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','ĸ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ŋ','ŋ',
			 'Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ',
			 'Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ź','ź','Ż','ż','Ž','ž','ſ','Ƕ','Ơ','ơ','Ư','ư',
			 'Ǆ','ǅ','ǆ','Ǉ','ǉ','ǈ','Ǌ','ǋ','ǌ','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǟ','ǟ','Ǡ',
			 'ǡ','Ǣ','ǣ','Ǥ','ǥ','Ǧ','ǧ','Ǩ','ǩ','Ǫ','ǫ','Ǭ','ǭ','ǯ','Ǯ','ǰ','Ǳ','ǲ','ǳ','Ǵ','ǵ','Ǹ','ǹ','Ǻ','ǻ','Ǽ','ǽ','Ǿ',
			 'ǿ','Ȁ','ȁ','Ȃ','ȃ','Ȅ','ȅ','Ȇ','ȇ','Ȉ','ȉ','Ȋ','ȋ','Ȍ','ȍ','Ȏ','ȏ','Ȑ','ȑ','Ȓ','ȓ','Ȕ','ȕ','Ȗ','ȗ','Ș','ș','Ț',
			 'ț','Ȟ','ȟ','Ṯ','ṯ','Ḥ','ḥ','Ȥ','ȥ','Ȧ','ȧ','Ȩ','ȩ','Ȫ','ȫ','Ȭ','ȭ','Ȯ','ȯ','Ȱ','ȱ','Ȳ','ȳ','ɑ','Ə','ə','Ạ','ạ',
			 'Ả','ả','Ấ','ấ','Ầ','ầ','Ẩ','ẩ','Ẫ','ẫ','Ậ','ậ','Ắ','ắ','Ằ','ằ','Ẳ','ẳ','Ẵ','ẵ','Ặ','ặ','Ẹ','ẹ','Ẻ','ẻ','Ẽ','ẽ',
			 'Ế','ế','Ề','ề','Ể','ể','Ễ','ễ','Ệ','ệ','Ỉ','ỉ','Ị','ị','Ọ','ọ','Ỏ','ỏ','Ố','ố','Ồ','ồ','Ổ','ổ','Ỗ','ỗ','Ộ','ộ',
			 'Ớ','ớ','Ờ','ờ','Ở','ở','Ỡ','ỡ','Ợ','ợ','Ụ','ụ','Ủ','ủ','Ứ','ứ','Ừ','ừ','Ử','ử','Ữ','ữ','Ự','ự','Ỳ','ỳ','Ỵ','ỵ',
			 'Ỷ','ỷ','Ỹ','ỹ','J̌','ǰ','ƀ','Ƃ','ƃ','Ƅ','ƅ','Ƈ','ƈ','Ƌ','ƌ','ƍ','Ƒ','ƒ','Ƙ','ƙ','ƚ','ƞ','ƪ','ƫ','Ƭ','ƭ','ƴ','Ƶ',
			 'ƶ','Ƹ','ƹ','ƺ','Ƿ','Ǝ','ǝ','Ș','ș','Ț','ț','Ȝ','ȝ','ȡ','Ȣ','ȣ','Ɓ','Ɔ','Ɖ','Ɗ','Ɛ','Ɠ','Ɨ','Ɩ','Ɲ','Ɵ','Ʈ','Ʊ',
			 'Ʋ','Ʒ','Ḃ','ḃ','ḋ','Ḋ','Ḟ','ḟ','ṁ','Ṁ','ṗ','Ṗ','ṡ','Ṡ','Ṫ','ṫ','Ẁ','ẁ','Ẃ','ẃ','Ẅ','ẅ','ẛ',
			],
			['A','a','A','a','A','a','A','a','A','a','A','a','Ae','ae','C','c','E','e','E','e','E','e','E','e','I','i','I','i',
			 'I','i','I','i','D','d','N','n','O','o','O','o','O','o','O','o','O','o','O','o','U','u','U','u','U','u','U','u',
			 'Y','y','Th','th','s','Y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Dj','dj','E','e',
			 'E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i',
			 'I','i','i','Ij','ij','J','j','K','k','k','L','l','L','l','L','l','L','l','L','l','N','n','N','n','N','n','N','N',
			 'n','O','o','O','o','O','o','Oe','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T',
			 't','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Z','z','Z','z','Z','z','s','H','O','o','U',
			 'u','Dz','Dz','dz','Lj','lj','lj','Nj','Nj','nj','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u',
			 'A','a','A','a','AE','ae','G','g','G','g','K','k','O','o','O','o','dz','dz','J','Dz','Dz','dz','G','g','N','n','A',
			 'a','ae','ae','O','o','A','a','A','a','E','e','E','e','I','i','I','i','O','o','O','o','R','r','R','r','U','u','U',
			 'u','S','s','T','t','H','h','T','t','H','h','Z','z','A','a','E','e','O','o','O','o','O','o','O','o','Y','y','a','a',
			 'a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','A','a','E','e','E','e',
			 'E','e','E','e','E','e','E','e','E','e','E','e','I','i','I','i','O','o','O','o','O','o','O','o','O','o','O','o','O',
			 'o','O','o','O','o','O','o','O','o','O','o','U','u','U','u','U','u','U','u','U','u','U','u','U','u','Y','y','Y','y',
			 'Y','y','Y','y','J','j','b','B','b','B','b','C','c','D','d','d','F','f','K','k','l','n','sh','t','T','t','y','Z','z',
			 'Zh','zh','zh','','E','e','S','s','T','t','J','j','d','Ou','ou','B','O','D','D','E','G','I','I','N','O','T','U','V',
			 'Zh','B','b','d','D','F','f','m','M','p','P','s','S','T','t','W','w','W','w','W','w','ss',
			 ],
			 strtr($string, $secondary)
		);
		// strip characters outside of Latin Basic if forced:
		return $force ? preg_replace('/[^\x20-\x7E]/u', '', $string) : $string;
	}

public static function unicode_to_latin($string, $force = FALSE, $locale = FALSE) {

		if (!preg_match('/[^\x20-\x{024F}]/u', $string)) {
			return $string;
		}

		// http://www.loc.gov/catdir/cpso/romanization/russian.pdf
		// http://unicode.org/repos/cldr/trunk/common/transforms/Cyrillic-Latin.xml
		$string = str_replace(
		   ['Я','ч','Ч','э','Э','є','Є','ш','Ш','щ','Щ','ѕ','Ѕ','ю','Ю','і','І','љ','Љ','њ','Њ','ћ','Ћ','џ','Џ','а',
			'А','ә','Ә','б','Б','в','В','ґ','Ґ','ғ','Ғ','ҕ','Ҕ','г','Г','д','Д','ђ','Ђ','е','Е','ж','Ж','з','З','й',
			'Й','и','И','қ','Қ','к','К','л','Л','м','М','н','Н','о','О','п','П','р','Р','с','С','т','Т','у','У','ф',
			'Ф','х','Х','ц','Ц','ы','Ы','Ъ','ъ','Ь','ь','Я','я'],
		   ['Â','č','Č','è','È','e','E','š','Š','šč','ŠČ','dz','Dz','û','Û','ì','Ì','lj','Lj','nj','Nj','ć','Ć','dž',
			'DŽ','a','A','ä','Ä','b','B','v','V','ǵ','Ǵ','g','G','g','G','g','G','d','D','đ','Đ','e','E','ž','Ž','z',
			'Z','j','J','i','I','k','K','k','K','l','L','m','M','n','N','o','O','p','P','r','R','s','S','t','T','u',
			'U','f','F','h','H','c','C','y','Y','˝','˝','´','´','Ja','ja'],
		   $string
		);

		// http://unicode.org/repos/cldr/trunk/common/transforms/Greek-Latin.xml
   		// http://unicode.org/repos/cldr/trunk/common/transforms/Greek-Latin-BGN.xml
		$string = str_replace(
		   ['α','Α','η','Η','φ','Ψ','Φ','ψ','ω','Ω','β','Β','γ','Γ','δ','Δ','ε','Ε','ζ','Ζ','θ','Θ','ι','Ι','κ','Κ',
			'λ','Λ','μ','Μ','ν','Ν','ξ','Ξ','ο','Ο','π','Π','ρ','Ρ','σ','Σ','τ','Τ','υ','Υ','χ','Χ','ά','Ά','έ','Έ',
			'ή','Ή','ί','Ί','ό','Ό','ύ','Ύ','ώ','Ώ',],
			['a','A','ē','Ē','ph','PS','PH','ps','ō','Ō','b','B','g','G','d','D','e','E','z','Z','th','TH','i','I','k',
			'K','l','L','m','M','n','N','x','X','o','O','p','P','r','R','s','S','t','T','y','Y','ch','CH','a','A','e',
			'E','ē','E','i','I','o','O','y','Y','ō','Ō'],
			$string
		);

		// http://www.loc.gov/catdir/cpso/romanization/arabic.pdf
		// https://en.wikipedia.org/wiki/Romanization_of_Arabic
		$string = str_replace(
		   ['ء','ا','ى','آ','ب','ت','ث','ج','ح','خ','د','ذ','ر','ز','س','ش','ص','ض','ط','ظ','ع','غ','ف','ق','ك','ل','م','ن',
			'ه','ة','و','ي','پ','چ',	'ڤ','گ','ڴ','ۋ','ژ','ڥ','۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','٠','١','٢','٣',
			'٤','٥','٦','٧','٨','٩','٫','٬','،','؛','؟','٪','ـ','َا','ُو','ِي','ً','ٌ','ٍ','َ','ُ','ِ','ّ','ْ','ٓ','ٔ','ٕ',],
		   ['ʾ','ā','à','ʾâ','b','t','ṯ','j','ḥ','ẖ','d','ḏ','r','z','s','š','ṣ','ḍ','ṭ','ẓ','ʿ','ġ','f','q','k','l',
		    'm','n','h','ẗ','w','y','p','zh','v','g','ñ','v','zh','v','0','1','2','3','4','5','6','7','8','9','0','1',
		    '2','3','4','5','6','7','8','9',',','.',',',';','?','%','','ā','ū','ī','an','un','in','a','u','i','̃ ','̊ ',
		    '̂ ','̉ ','̹ ',],
			$string
		);

		// http://unicode.org/repos/cldr/trunk/common/transforms/Hebrew-Latin-BGN.xml
		$string = str_replace(
		   ['ח','צ','ץ','ש','ת','א','ב','ג','ד','ה','ו','ז','ט','י','כ','ך','ל','מ','ם','נ','ן','ס','ע',
		    'פ','ף','ק','ר','‎ֲ‎','‎ֲ‎','‎ָ‎','‎ָ‎','‎ֱ‎','‎ֱ‎','‎ֵ‎','‎ֵ‎','‎ְ‎','‎ְ‎','‎ֹ‎','‎ֹ‎','ִ','ֻ','ַ','ֶ','ֳ',],
		   ['ẖ','ẕ','ẕ','ş','ţ','ʼ','b','g','d','h','w','z','t','y','k','k','l','m','m','n','n','s',
		    'ʻ','p','p','q','r','à','a','á','a','è','e','é','e','e','e','ò','o','i','u','a','e','o',],
			$string
		);

		// https://en.wikipedia.org/wiki/ISO_11940
		$string = str_replace(
		   ['ก','ข','ฃ','ค','ฅ','ฆ','ง','จ','ฉ','ช','ซ','ฌ','ญ','ฎ','ฏ','ฐ','ฑ','ฒ','ณ','ด','ต','ถ','ท','ธ','น','บ','ป','ผ',
		    'ฝ','พ','ฟ','ภ','ม','ย','ร','ฤ','ล','ฦ','ว','ศ','ษ','ส','ห','ฬ','อ','ฮ','ะ','–ั','า','ำ','–ิ','–ี','–ึ','–ื','–ุ',
		    '–ู','เ','แ','โ','ใ','ไ','ฤ','ฤๅ','ฦ','ฦๅ','ย','ว','อ','–่','–้','–๊','–๋','–็','–์','–๎','–ํ','–ฺ','ๆ','ฯ','๏','ฯ',
		    '๚','๛','๐','๑','๒','๓','๔','๕','๖','๗','๘','๙'],
		   ['k','k̄h','ḳ̄h','kh','k̛h','ḳh','ng','c','c̄h','ch','s','c̣h','ỵ','ḍ','ṭ','ṭ̄h','ṯh','t̛h','ṇ','d','t','t̄h','th','ṭh',
		    'n','b','p','p̄h','f̄','ph','f','p̣h','m','y','r','v','l','ł','w','ṣ̄','s̛̄','s̄','h̄','ḷ','x','ḥ','a','ạ','ā','å','i',
		    'ī','ụ','ụ̄','u','ū','e','æ','o','ı','ị','v','vɨ','ł','łɨ','y','w','x','–̀','–̂','–́','–̌','–̆','–̒','~','–̊','–̥','«',
		    'ǂ','§','ǀ','ǁ','»','0','1','2','3','4','5','6','7','8','9'],
		   $string
		);

		// http://jrgraphix.net/r/Unicode/16A0-16FF
		// https://en.wikipedia.org/wiki/Elder_Futhark
		// https://en.wikipedia.org/wiki/Younger_Futhark
		$string = str_replace(
		   ['ᚠ','ᚡ','ᚢ','ᚣ','ᚤ','ᚦ','ᚧ','ᚨ','ᛅ','ᛆ','ᚪ','ᛇ','ᚫ','ᛠ','ᚬ','ᚭ','ᚱ','ᚲ','ᚴ','ᚳ','ᛣ','ᛤ','ᛥ','ᚷ','ᚵ','ᚶ','ᚸ','ᚹ','ᚥ',
		    'ᚺ','ᚼ','ᚽ','ᚻ','ᚾ','ᚿ','ᛀ','ᛁ','ᛃ','ᛄ','ᛡ','ᛈ','ᛢ','ᛔ','ᛕ','ᛉ','ᛎ','ᛦ','ᛧ','ᛨ','ᛍ','ᛊ','ᛋ','ᛌ','ᛏ','ᛐ','ᛑ','ᛒ','ᛓ','ᛖ',
		    'ᛂ','ᛗ','ᛘ','ᛙ','ᛚ','ᛛ','ᛜ','ᛝ','ᛟ','ᚩ','ᚮ','ᚯ','ᚰ','ᛞ','ᛩ','ᛪ',],
		   ['f','v','u','u','y','þ','þ','a','a','a','a','æ','æ','ea','ą','ą','r','k','k','k','k','k','st','g','g','g',
		    'g','w','w','h','h','h','h','n','n','n','i','j','j','j','p','p','p','p','z','z','ʀ','ʀ','ʀ','c','s','s','s',
		    't','t','d','b','b','e','e','m','m','m','l','l','ŋ','ŋ','o','o','o','o','o','d','q','x',],
		   $string
		);

		// http://unicode.org/repos/cldr/trunk/common/transforms/Katakana-Latin-BGN.xml
		$string = str_replace(
		   ['ア','イ','ウ','エ','オウ','オ','カ','キョウ','キュウ','キャ','キョ','キュ','キ','ク','ケ','コウ','コ','サ','ショウ','シュウ',
			'シャ','ショ','シュ','シ','ス','セ','ソウ','ソ','タ','チョウ','チュウ','チャ','チョ','チュ','チ','ツ','テ','トウ','ト','ナ',
			'ニョウ','ニュウ','ニャ',	'ニョ','ニュ','ニ','ヌ','ネ','ノウ','ノ','ハ','ヒョウ','ヒュウ','ヒャ','ヒョ','ヒュ','ヒ','フ','ヘ','ホウ',
			'ホ','マ','ミョウ',	'ミュウ','ミャ','ミョ','ミュ','ミ','ム','メ','モウ','モ','ヤ','ユ','ヨウ','ヨ','ラ','リョウ','リュウ','リャ','リョ',
			'リュ','リ','ル','レ','ロウ','ロ','ワ','ヰ','ヱ','ヲ','ン','ガ','ギョウ','ギュウ','ギャ','ギョ','ギュ','ギ','グ','ゲ','ゴウ',
			'ゴ','ザ','ジョウ',	'ジュウ','ジャ','ジョ','ジュ','ジ','ズ','ゼ','ゾウ','ゾ','ダ','ヂ','ヅ','デ','ドウ','ド','バ','ビョウ',
			'ビュウ','ビャ','ビョ','ビュ','ビ','ブ','ベ','ボウ','ボ','パ','ピョウ','ピュウ','ピャ','ピョ','ピュ','ピ','プ','ペ','ポウ','ポ',
			'ヴ','あ','い','う','え','おう','お','か','きょう','きゅう','きゃ','きょ','きゅ','き','く','け','こう','こ','さ','しょう','しゅう',
			'しゃ','しょ','しゅ','し','す','せ','そう','そ','た','ちょう','ちゅう','ちゃ','ちょ','ちゅ','ち','つ','て','とう','と','な','にょう',
			'にゅう','にゃ','にょ','にゅ','に','ぬ','ね','のう','の','は','ひょう','ひゅう','ひゃ','ひょ','ひゅ','ひ','ふ','へ','ほう','ほ',
			'ま','みょう',	'みゅう','みゃ','みょ','みゅ','み','む','め','もう','も','や','ゆ','よう','よ','ら','りょう','りゅう','りゃ','りょ',
			'りゅ','り','る','れ','ろう','ろ','わ','ゐ','ゑ','を','ん','が','ぎょう','ぎゅう','ぎゃ','ぎょ','ぎゅ','ぎ','ぐ','げ','ごう','ご',
			'ざ','じょう','じゅう','じゃ','じょ','じゅ','じ','ず','ぜ','ぞう','ぞ','だ','ぢ','づ','で','どう','ど','ば','びょう','びゅう','びゃ',
			'びょ','びゅ','び','ぶ','べ','ぼう','ぼ','ぱ','ぴょう','ぴゅう','ぴゃ','ぴょ','ぴゅ','ぴ','ぷ','ぺ','ぽう','ぽ','ゔ','ゃ','ゅ','ょ',	],
		   ['a','i','u','e','ō','o','ka','kyō','kyū','kya','kyo','kyu','ki','ku','ke','kō','ko','sa','shō','shū','sha',
			'sho','shu','shi','su','se','sō','so','ta','chō','chū','cha','cho','chu','chi','tsu','te','tō','to','na',
			'nyō','nyū','nya','nyo','nyu','ni','nu','ne','nō','no','ha','hyō','hyū','hya','hyo','hyu','hi','fu','he',
			'hō','ho','ma','hyō','hyū','hya','hyo','hyu','mi','mu','me','mō','mo','ya','yu','yō','yo','ra','ryō','ryū',
			'rya','ryo','ryu','ri','ru','re','rō','ro','wa','i','e','o','n','ga','gyō','gyū','gya','gyo','gyu','gi',
			'gu','ge','gō','go','za','jō','jū','ja','jo','ju','ji','zu','ze','zō','zo','da','ji','zu','de','dō','do',
			'ba','byō','byū','bya','byo','byu','bi','bu','be','bō','bo','pa','pyō','pyū','pya','pyo','pyu','pi','pu',
			'pe','pō','po','v','a','i','u','e','ō','o','ka','kyō','kyū','kya','kyo','kyu','ki','ku','ke','kō','ko','sa',
			'shō','shū','sha','sho','shu','shi','su','se','sō','so','ta','chō','chū','cha','cho','chu','chi','tsu','te',
			'tō','to','na','nyō','nyū','nya','nyo','nyu','ni','nu','ne','nō','no','ha','hyō','hyū','hya','hyo','hyu',
			'hi','fu','he','hō','ho','ma','hyō','hyū','hya','hyo','hyu','mi','mu','me','mō','mo','ya','yu','yō','yo',
			'ra','ryō','ryū','rya','ryo','ryu','ri','ru','re','rō','ro','wa','i','e','o','n','ga','gyō','gyū','gya',
			'gyo','gyu','gi','gu','ge','gō','go','za','jō','jū','ja','jo','ju','ji','zu','ze','zō','zo','da','ji','zu',
			'de','dō','do','ba','byō','byū','bya','byo','byu','bi','bu','be','bō','bo','pa','pyō','pyū','pya','pyo',
			'pyu','pi','pu','pe','pō','po','v','','','',],
			preg_replace(
				['/(ッ|っ)([カキクケコ]|[かきくけこ])/u','/(ッ|っ)([サシスセソ]|[さしすせそ])/u','/(ッ|っ)([タチツテト]|[たちつてと])/u',
				 '/(ッ|っ)([パピプペポ]|[ぱぴぷぺぽ])/u','/(ン|ん)([バビブベボパピプペポマミムメモ]|[ばびぶべぼぱぴぷぺぽまみむめも])/u',
				 '/(ン|ん)([ヤユヨアイウエオ]|[やゆよあいうえお])/u'],
				['k$2','s$2','t$2','p$2','m$2','n’$2',],
				$string
			)
		);
		// strip characters below Latin Extended-B if forced:
		return $force ? preg_replace('/[^\x20-\x{024F}]/u', '', $string) : $string;
	}
}