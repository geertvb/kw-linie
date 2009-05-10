package referenceData
{
	public class BunkerExtHolder
	{
		private static var holder : Object = createHolder();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{ext: ""},
				{ext: "bis"}
			];
			
			return result;
		}


	}
}