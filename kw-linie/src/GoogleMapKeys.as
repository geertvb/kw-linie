// ActionScript file
import mx.utils.URLUtil;

var keys = {
	"localhost":"ABQIAAAARHlxm1tnIyh53Qky1yRvaxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRSxc4qL8cIlXY1g_19AWODs4-61Q",
	"j-devit.be":"ABQIAAAARHlxm1tnIyh53Qky1yRvaxTbkIhfUKWrw_W4kq9pNUJCSXBKZxTAOgH7Plo-bag49SWh3b0VdoqMsA"
};

public function endsWith(str: String, suffix: String) : Boolean {
	var pos: int = str.lastIndexOf(suffix);
	if (pos >= 0) {
		return pos + suffix.length == str.length;
	} else {
		return false;
	}
}

public function getKey(url : String) : String {
	var servername : String = URLUtil.getServerName(url);
	for (var key : String in keys) {
		if (endsWith(servername, key)) {
			return keys[key];
		}
	}
	return null;
}

