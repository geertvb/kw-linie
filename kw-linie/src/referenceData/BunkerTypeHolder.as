package referenceData
{
	public class BunkerTypeHolder
	{
		
		private static var holder: Object = createHolder();
		private static var holder2: Object = createHolder2();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function getInstance2() : Object {
			return holder2;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{type: "verdediging 1e lijn"},
				{type: "verdediging 2e lijn"},
				{type: "verdediging antitankcentrum"},
				{type: "bruggenhoofd mechelen"},
				{type: "commando 1e lijn"},
				{type: "commando 2e lijn"},
				{type: "connectiekamer"}
			];
			
			return result;
		}

		public static function createHolder2() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{type: null,label: "Alle"},
				{type: "verdediging 1e lijn",label: "verdediging 1e lijn"},
				{type: "verdediging 2e lijn",label: "verdediging 2e lijn"},
				{type: "verdediging antitankcentrum",label: "verdediging antitankcentrum"},
				{type: "bruggenhoofd mechelen",label: "bruggenhoofd mechelen"},
				{type: "commando 1e lijn",label: "commando 1e lijn"},
				{type: "commando 2e lijn",label: "commando 2e lijn"},
				{type: "connectiekamer",label: "connectiekamer"}
			];
			
			return result;
		}

	}

}