package referenceData
{
	public class BunkerCodeHolder
	{
		
		private static var holder : Object = createHolder();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{code: "A"},
				{code: "Bb"},
				{code: "BL"},
				{code: "C"},
				{code: "Do"},
				{code: "F"},
				{code: "GH"},
				{code: "H"},
				{code: "Ha"},
				{code: "He"},
				{code: "Ib"},
				{code: "KO"},
				{code: "KR"},
				{code: "L"},
				{code: "LW"},
				{code: "ML"},
				{code: "MS"},
				{code: "P"},
				{code: "Pe"},
				{code: "Ps"},
				{code: "Ro"},
				{code: "Ry"},
				{code: "Sb"},
				{code: "Te"},
				{code: "Th"},
				{code: "TPM"},
				{code: "VA"},
				{code: "VB"},
				{code: "VC"},
				{code: "VD"},
				{code: "We"}
			];
			
			return result;
		}

	}
}