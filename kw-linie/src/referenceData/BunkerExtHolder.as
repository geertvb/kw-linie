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
				{ext: "1"},
				{ext: "2"},
				{ext: "/1"},
				{ext: "b"},
				{ext: "bis"}
			];
			
			return result;
		}


	}
}