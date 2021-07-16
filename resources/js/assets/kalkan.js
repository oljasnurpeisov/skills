var debug = true;

function Kalkan(host, port, debug, onReady, onError)
{
    this.debug = debug || false;

    this.host = host || '127.0.0.1';
    this.port = port || '13579';

    this.onReady = onReady || null;
    this.onError = onError || null;

    this.heartbeat_msg = '--heartbeat--';
    this.heartbeat_interval = null;

    this.missed_heartbeats = 0;
    this.missed_heartbeats_limit_min = 300;
    this.missed_heartbeats_limit_max = 5000;
    this.missed_heartbeats_limit = this.missed_heartbeats_limit_min;

    this.callback = null;
    this.ready = null;

    this.storageName = null;
    this.fileExtension = null;
    this.currentDirectory = null;

    this.socket = this.getSocket();

    var parent = this;

    this.socket.onopen = function (event) {

        if (parent.heartbeat_interval === null) {
            parent.missed_heartbeats = 0;
            parent.heartbeat_interval = setInterval(parent.sendPing.bind(parent), 2000);
        }

        console.log("Connection opened");

        if(parent.onReady) parent.onReady();
    };

    this.socket.onclose = function (event)
    {
        if (event.wasClean) {
            console.log('connection has been closed');
        } else {
            console.log('Connection error');
            if(parent.onError) parent.onError();
        }

        console.log('Code: ' + event.code + ' Reason: ' + event.reason);
        console.log(event);
    };

    this.socket.onmessage = function (event)
    {
        if (event.data === parent.heartbeat_msg) {
            parent.missed_heartbeats = 0;
            return;
        }

        var result = JSON.parse(event.data);

        var rw = {
            result: result['result'],
            secondResult: result['secondResult'],
            errorCode: result['errorCode'],
            responseObject: result['responseObject'],
            message: result.message,
            getResult: function () {
                return this.result;
            },
            getSecondResult: function () {
                return this.secondResult;
            },
            getErrorCode: function () {
                return this.errorCode;
            },
            getResponseObject: function () {
                return this.responseObject;
            },
            getCode: function () {
                return this.code;
            }
        };

        if(parent.callback)
            parent.callback(rw);

        parent.setMissedHeartbeatsLimitToMin();
    };
}

Kalkan.prototype.getSocket = function()
{
    return new WebSocket('wss://' + this.host + ':' + this.port + '/');
};

Kalkan.prototype.setMissedHeartbeatsLimitToMax = function() {
    this.missed_heartbeats_limit = this.missed_heartbeats_limit_max;
};

Kalkan.prototype.setMissedHeartbeatsLimitToMin = function() {
    this.missed_heartbeats_limit = this.missed_heartbeats_limit_min;
};

Kalkan.prototype.sendMessage = function(msg, callback)
{
    this.callback = callback;
    this.setMissedHeartbeatsLimitToMax();
    this.socket.send(JSON.stringify(msg));
};

Kalkan.prototype.sendPing = function() {

    console.log("pinging...");

    try {
        this.missed_heartbeats++;
        if (this.missed_heartbeats >= this.missed_heartbeats_limit)
            throw new Error("Too many missed heartbeats.");
        this.socket.send(this.heartbeat_msg);
    } catch (e) {
        clearInterval(this.heartbeat_interval);
        this.heartbeat_interval = null;
        console.warn("Closing connection. Reason: " + e.message);
        this.socket.close();
    }
};

/**
 * Browse key store
 *
 * @param storageName
 * @param fileExtension
 * @param currentDirectory
 * @param callback
 */
Kalkan.prototype.browseKeyStore = function(storageName, fileExtension, currentDirectory, callback)
{
    var browseKeyStore = {
        "method": "browseKeyStore",
        "args": [storageName, fileExtension, currentDirectory]
    };

    this.sendMessage(browseKeyStore, callback);
};

/**
 * Check application version
 *
 * @param callback
 */
Kalkan.prototype.checkNCAVersion = function(callback)
{
    var checkNCAVersion = {
        "method": "checkNCAVersion",
        "args": []
    };

    this.sendMessage(checkNCAVersion, callback);
};

/**
 * Load slot list
 *
 * @param storageName
 * @param callback
 */
Kalkan.prototype.loadSlotList = function(storageName, callback)
{
    var loadSlotList = {
        "method": "loadSlotList",
        "args": [storageName]
    };

    this.sendMessage(loadSlotList, callback);
};

/**
 * Show file chooser dialog
 *
 * @param fileExtension
 * @param currentDirectory
 * @param callback
 */
Kalkan.prototype.showFileChooser = function(fileExtension, currentDirectory, callback)
{
    var showFileChooser = {
        "method": "showFileChooser",
        "args": [fileExtension, currentDirectory]
    };

    this.sendMessage(showFileChooser, callback);
};

/**
 * Get keys
 *
 * @param storageName
 * @param storagePath
 * @param password
 * @param type
 * @param callback
 */
Kalkan.prototype.getKeys = function(storageName, storagePath, password, type, callback)
{
    var getKeys = {
        "method": "getKeys",
        "args": [storageName, storagePath, password, type]
    };

    this.sendMessage(getKeys, callback);
};

/**
 * Get expiration date
 *
 * @param storageName
 * @param storagePath
 * @param alias
 * @param password
 * @param callback
 */
Kalkan.prototype.getNotAfter = function(storageName, storagePath, alias, password, callback)
{
    var getNotAfter = {
        "method": "getNotAfter",
        "args": [storageName, storagePath, alias, password]
    };

    this.sendMessage(getNotAfter, callback);
};

/**
 * Get beginning date
 *
 * @param storageName
 * @param storagePath
 * @param alias
 * @param password
 * @param callback
 */
Kalkan.prototype.getNotBefore = function(storageName, storagePath, alias, password, callback)
{
    var getNotBefore = {
        "method": "getNotBefore",
        "args": [storageName, storagePath, alias, password]
    };

    this.sendMessage(getNotBefore, callback);
};

/**
 * Get subject DN
 *
 * @param storageName
 * @param storagePath
 * @param alias
 * @param password
 * @param callback
 */
Kalkan.prototype.getSubjectDN = function(storageName, storagePath, alias, password, callback)
{
    var getSubjectDN = {
        "method": "getSubjectDN",
        "args": [storageName, storagePath, alias, password]
    };

    this.sendMessage(getSubjectDN, callback);
};

Kalkan.prototype.getIssuerDN = function(storageName, storagePath, alias, password, callback)
{
    var getIssuerDN = {
        "method": "getIssuerDN",
        "args": [storageName, storagePath, alias, password]
    };

    this.sendMessage(getIssuerDN, callback);
};

Kalkan.prototype.getRdnByOid = function(storageName, storagePath, alias, password, oid, oidIndex, callback)
{
    var getRdnByOid = {
        "method": "getRdnByOid",
        "args": [storageName, storagePath, alias, password, oid, oidIndex]
    };

    this.sendMessage(getRdnByOid, callback);
};

Kalkan.prototype.signPlainData = function(storageName, storagePath, alias, password, dataToSign, callback)
{
    var signPlainData = {
        "method": "signPlainData",
        "args": [storageName, storagePath, alias, password, dataToSign]
    };

    this.sendMessage(signPlainData, callback);
};

Kalkan.prototype.verifyPlainData = function(storageName, storagePath, alias, password, dataToVerify, base64EcodedSignature, callback)
{
    var verifyPlainData = {
        "method": "verifyPlainData",
        "args": [storageName, storagePath, alias, password, dataToVerify, base64EcodedSignature]
    };

    this.sendMessage(verifyPlainData, callback);
};

Kalkan.prototype.createCMSSignature = function(storageName, storagePath, alias, password, dataToSign, attached, callback)
{
    var createCMSSignature = {
        "method": "createCMSSignature",
        "args": [storageName, storagePath, alias, password, dataToSign, attached]
    };

    this.sendMessage(createCMSSignature, callback);
};

Kalkan.prototype.createCMSSignatureFromFile = function(storageName, storagePath, alias, password, filePath, attached, callback)
{
    var createCMSSignatureFromFile = {
        "method": "createCMSSignatureFromFile",
        "args": [storageName, storagePath, alias, password, filePath, attached]
    };

    this.sendMessage(createCMSSignatureFromFile, callback);
};

Kalkan.prototype.verifyCMSSignature = function(signatureToVerify, signedData, callback)
{
    var verifyCMSSignature = {
        "method": "verifyCMSSignature",
        "args": [signatureToVerify, signedData]
    };

    this.sendMessage(verifyCMSSignature, callback);

};

Kalkan.prototype.verifyCMSSignatureFromFile = function(signatureToVerify, filePath, callback)
{
    var verifyCMSSignatureFromFile = {
        "method": "verifyCMSSignatureFromFile",
        "args": [signatureToVerify, filePath]
    };

    this.sendMessage(verifyCMSSignatureFromFile, callback);
};

Kalkan.prototype.signXml = function(storageName, storagePath, alias, password, xmlToSign, callback)
{
    var signXml = {
        "method": "signXml",
        "args": [storageName, storagePath, alias, password, xmlToSign]
    };

    this.sendMessage(signXml, callback);
};

Kalkan.prototype.signXmlByElementId = function(storageName, storagePath, alias, password, xmlToSign, elementName, idAttrName, signatureParentElement, callback)
{
    var signXmlByElementId = {
        "method": "signXmlByElementId",
        "args": [storageName, storagePath, alias, password, xmlToSign, elementName, idAttrName, signatureParentElement]
    };

    this.sendMessage(signXmlByElementId, callback);
};

Kalkan.prototype.authXml = function(storageName, keyType, xmlToSign, callback)
{
    var signXml = {
        'module': 'kz.gov.pki.knca.commonUtils',
        "method": "signXml",
        "args": [storageName, keyType, xmlToSign, "", ""]
    };

    this.sendMessage(signXml, callback);
};

Kalkan.prototype.authXmls = function(storageName, keyType, xmlsToSign, callback)
{
    var signXml = {
        'module': 'kz.gov.pki.knca.commonUtils',
        "method": "signXmls",
        "args": [storageName, keyType, xmlsToSign, "", ""]
    };

    this.sendMessage(signXml, callback);
};

Kalkan.prototype.verifyXml = function(xmlSignature, callback)
{
    var verifyXml = {
        "method": "verifyXml",
        "args": [xmlSignature]
    };

    this.sendMessage(verifyXml, callback);
};

Kalkan.prototype.verifyXmlById = function(xmlSignature, xmlIdAttrName, signatureElement, callback)
{
    var verifyXml = {
        "method": "verifyXml",
        "args": [xmlSignature, xmlIdAttrName, signatureElement]
    };

    this.sendMessage(verifyXml, callback);
};

Kalkan.prototype.getHash = function(data, digestAlgName, callback)
{
    var getHash = {
        "method": "getHash",
        "args": [data, digestAlgName]
    };

    this.sendMessage(getHash, callback);
};

/**
 * Run applet emulator
 *
 * @param attributes
 * @param parameters
 */
Kalkan.prototype.runApplet = function(attributes, parameters)
{
    document.write("<p>hello</p>");
};
