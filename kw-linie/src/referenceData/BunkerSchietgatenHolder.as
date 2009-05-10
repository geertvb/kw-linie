package referenceData
{
	public class BunkerSchietgatenHolder
	{
		
		private static var holder: Object = createHolder();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{schietgaten: "1"},
				{schietgaten: "2"},
				{schietgaten: "3"},
				{schietgaten: "nvt"}
			];
			
			return result;
		}
	}
}