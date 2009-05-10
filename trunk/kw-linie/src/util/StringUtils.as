package util
{
	public class StringUtils
	{
		
		public static function isEmpty(s: String) : Boolean {
			return (s == null) || (s.length == 0);
		}
		
		public static function leftPadding(s: String, width: int, padding: String = " ") : String {
			var result: String = s;
			while (result.length < width) {
				result = padding + result;
			}
			return result;
		}
		
		public static function join(values: Array, sep: String = " ") : String {
			var result: String = "";
			for (var i:int=0; i<values.length; i++) {
				var value: String = values[i];
				if (!isEmpty(value)) {
					if (!isEmpty(result)) {
						result += sep;
					}
					result += value;
				}
			}
			return result;
		}
		
	}
}