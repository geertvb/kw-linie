package
{
	
import mx.utils.URLUtil;

	public class GoogleMapKeys {
		
		private static var keys: Object = {
			"localhost":"ABQIAAAARHlxm1tnIyh53Qky1yRvaxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRSxc4qL8cIlXY1g_19AWODs4-61Q",
			"j-devit.be":"ABQIAAAARHlxm1tnIyh53Qky1yRvaxTbkIhfUKWrw_W4kq9pNUJCSXBKZxTAOgH7Plo-bag49SWh3b0VdoqMsA",
			"kwlinie.be":"ABQIAAAAL4gDNG8jsapnfnIxxSbmXBQgfeSkNN7qNgPfoxJaK42Uxer2KxS2iBxqn9eykR2TxIcr6N3_pEWP1A"
		};
		
		private static function endsWith(str: String, suffix: String) : Boolean {
			var pos: int = str.lastIndexOf(suffix);
			if (pos >= 0) {
				return pos + suffix.length == str.length;
			} else {
				return false;
			}
		}
		
		public static function getKey(url : String) : String {
			var servername : String = URLUtil.getServerName(url);
			for (var key : String in keys) {
				if (endsWith(servername, key)) {
					return keys[key];
				}
			}
			return null;
		}

	}

}
