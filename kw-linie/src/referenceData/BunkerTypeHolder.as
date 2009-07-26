package referenceData
{
	public class BunkerTypeHolder
	{
		
		private static var holder: Object = createHolder();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{type: "verdediging 1e lijn"},
				{type: "verdediging 2e lijn"},
				{type: "verdediging antitankcentrum"},
				{type: "kanaalbunker"},
				{type: "commando 1e lijn"},
				{type: "commando 2e lijn"},
				{type: "connectiekamer"}
			];
			
			return result;
		}

	}

}