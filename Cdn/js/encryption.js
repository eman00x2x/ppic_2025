/** https://adnan-tech.com/backup/end-to-end-encryption-js-php-mysql#encrypt-message */

function encodeText(data) {
	if ('TextEncoder' in window) {
		return new TextEncoder('utf-8').encode(data);
	}
	return undefined;
}

async function encrypt(data, publicKey, privateKey) {
	
	const publicKeyObj = await window.crypto.subtle.importKey(
		"jwk",
		publicKey,
		{
			name: "ECDH",
			namedCurve: "P-256",
		},
		true,
		[]
	);

	const privateKeyObj = await window.crypto.subtle.importKey(
		"jwk",
		privateKey,
		{
			name: "ECDH",
			namedCurve: "P-256",
		},
		true,
		["deriveKey", "deriveBits"]
	);

	const derivedKey = await window.crypto.subtle.deriveKey(
		{ name: "ECDH", public: publicKeyObj },
		privateKeyObj,
		{ name: "AES-GCM", length: 256 },
		true,
		["encrypt", "decrypt"]
	)

	const encodedText = encodeText(data);
	const generatedIv = new TextEncoder().encode(new Date().getTime());
	const encryptedData = await window.crypto.subtle.encrypt(
		{ name: "AES-GCM", iv: generatedIv },
		derivedKey,
		encodedText
	);

	const uintArray = new Uint8Array(encryptedData);
	const string = String.fromCharCode.apply(null, uintArray);
	const base64Data = btoa(string);
	const b64encodedIv = btoa(new TextDecoder("utf8").decode(generatedIv));

	return {
		encrypted: base64Data,
		iv: b64encodedIv
	};

}

async function decrypt(data, publicKey, privateKey) { 

	const publicKeyObj = await window.crypto.subtle.importKey(
		"jwk",
		publicKey,
		{
			name: "ECDH",
			namedCurve: "P-256",
		},
		true,
		[]
	);

	const privateKeyObj = await window.crypto.subtle.importKey(
		"jwk",
		privateKey,
		{
			name: "ECDH",
			namedCurve: "P-256",
		},
		true,
		["deriveKey", "deriveBits"]
	);

	const derivedKey = await window.crypto.subtle.deriveKey(
		{ name: "ECDH", public: publicKeyObj },
		privateKeyObj,
		{ name: "AES-GCM", length: 256 },
		true,
		["encrypt", "decrypt"]
	);

	const iv = new Uint8Array(atob(data.iv).split("").map(function (c) {
		return c.charCodeAt(0)
	}));

	const initializationVector = new Uint8Array(iv).buffer;
	const string = atob(data.user_message);
	const uintArray = new Uint8Array(
		[...string].map((char) => char.charCodeAt(0))
	);

	const decryptedData = await window.crypto.subtle.decrypt(
		{
			name: "AES-GCM",
			iv: initializationVector,
		},
		derivedKey,
		uintArray
	);

	const message = new TextDecoder().decode(decryptedData);

	return JSON.parse(message);
}

async function generateKey() { 

	const keyPair = await window.crypto.subtle.generateKey({
			name: 'ECDH',
			namedCurve: "P-256"
		}, true, [
			'deriveKey',
			'deriveBits'
	]);

	const publicKey = await window.crypto.subtle.exportKey(
		"jwk",
		keyPair.publicKey
	);

	const privateKey = await window.crypto.subtle.exportKey(
		"jwk",
		keyPair.privateKey
	);

	return {
		"publicKey": publicKey,
		"privateKey": privateKey
	}

}