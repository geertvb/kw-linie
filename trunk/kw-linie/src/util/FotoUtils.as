package util
{
	import flash.events.DataEvent;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.net.navigateToURL;
	
	import mx.core.Application;
	
	public class FotoUtils
	{
		
		public static function xmlToFoto(xml: XML) : Object {
			var foto: Object = new Object();
			foto.foto_id = int(xml.foto_id);
			foto.omschrijving = String(xml.omschrijving);
			foto.filename = String(xml.filename);
			foto.mimetype = String(xml.mimetype);
			foto.width = int(xml.width);
			foto.height = int(xml.height);
			foto.size = int(xml.size);
			return foto;
		}
		
		public static function navigateTo(foto_id: int, target: String = "_blank") : void {
			var parameters: URLVariables = new URLVariables();
			parameters.foto_id = foto_id;

			var request: URLRequest = new URLRequest();
			var appUrl: String = Application.application.url;
			var i: int = appUrl.lastIndexOf("/");
			request.url = appUrl.substring(0,i+1) + "download_foto.php";
			request.method = URLRequestMethod.GET;
			request.data = parameters;

			navigateToURL(request, target);
		}
		
		public static function upload(complete: Function = null) : void {
			var uploadFile : UploadFile = UploadFile.getInstance();
			uploadFile.url = "upload_foto.php";
			if (complete != null) {
				uploadFile.complete = function (event: DataEvent) : void {
					var xml: XML = new XML(event.data);
					var foto: Object = xmlToFoto(xml);
					complete(foto);
				}
			} else {
				uploadFile.complete = null;
			}
			uploadFile.upload();
		}

	}
}