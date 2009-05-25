package util
{
	import flash.events.DataEvent;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.net.navigateToURL;
	
	public class DocumentUtils
	{

		public static function xmlToDocument(xml: XML) : Object {
			var document: Object = new Object();
			document.document_id = int(xml.document_id);
			document.omschrijving = String(xml.omschrijving);
			document.filename = String(xml.filename);
			document.mimetype = String(xml.mimetype);
			document.size = int(xml.size);
			return document;
		}
		
		public static function navigateTo(document_id: int, target: String = "_blank") : void {
			var parameters: URLVariables = new URLVariables();
			parameters.document_id = document_id;

			var request: URLRequest = new URLRequest();
			request.url = "download_document.php";
			request.method = URLRequestMethod.GET;
			request.data = parameters;

			navigateToURL(request, target);
		}
		
		public static function upload(complete: Function = null) : void {
			var uploadFile : UploadFile = UploadFile.getInstance();
			uploadFile.url = "upload_document.php";
			if (complete != null) {
				uploadFile.complete = function (event: DataEvent) : void {
					var xml: XML = new XML(event.data);
					var document: Object = xmlToDocument(xml);
					complete(document);
				}
			} else {
				uploadFile.complete = null;
			}
			uploadFile.upload();
		}

	}
}