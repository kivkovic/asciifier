const fs = require('fs');

const source = fs.readFileSync('./src/Asciifier/Convert.php', 'utf8')
	.replace(/^\s*<\?php\s*/, '')
	.replace(/^\s*namespace [^;]+;\s*/, '')
	.replace(/^(\s*)(public|protected|private) ?(static)? function/mg, '$1$3')
	+ "\nexports.Convert = Convert;\n"
	+
	`
	const get_regex = (pattern) => {
		const match = pattern.match(new RegExp('^.(.+)\\\\' + pattern[0] + '([^' + pattern[0] + ']+)$'));
		match[1] = match[1].replace(/\\\\x{(....)}/, '\\\\u$1');
		return new RegExp(match[1], match[2] || '');
	};

	const preg_match = (pattern, string) => string.match(get_regex(pattern));

	const preg_replace = (pattern, replace, string) => {
		if (typeof replace === 'string') return string.replace(get_regex(pattern), replace);
		for (let i = 0; i < replace.length; i++) {
			string = string.replace(get_regex(pattern[i]), replace[i]);
		}
		return string;
	}

	const str_replace = (search, replace, subject) => {
		for (let i = 0; i < search.length; i++) {
			while (subject.indexOf(search[i]) !== -1) {
				subject = subject.replace(search[i], replace[i]);
			}
		}
		return subject;
	};

	const strtr = (string, dictionary) => {
		return str_replace(Object.keys(dictionary), Object.values(dictionary), string);
	};
	`;

fs.writeFileSync('./src/Asciifier/Convert.js', source, 'utf8');
