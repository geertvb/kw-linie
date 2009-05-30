package referenceData
{
	public class AnderObjectTypeHolder
	{
		
		private static var holder: Object = createHolder();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{type: "meerpaal"},
				{type: "brug"},
				{type: "sluis"},
				{type: "geassocieerd huis"},
				{type: "loopgracht"},
				{type: "antitankgracht"}
			];
			
			return result;
		}

	}
}