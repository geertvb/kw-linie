package referenceData
{
	public class BunkerCodeHolder
	{
		
		private static var holder : Object = createHolder();
		private static var holder2 : Object = createHolder2();
		
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
		
		public static function createHolder2() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{code: null,   label: "Alle"},
				{code: "A",   label: "A"},
				{code: "Bb",  label: "Bb"},
				{code: "BL",  label: "BL"},
				{code: "C",   label: "C"},
				{code: "Do",  label: "Do"},
				{code: "F",   label: "F"},
				{code: "GH",  label: "GH"},
				{code: "H",   label: "H"},
				{code: "Ha",  label: "Ha"},
				{code: "He",  label: "He"},
				{code: "Ib",  label: "Ib"},
				{code: "KO",  label: "KO"},
				{code: "KR",  label: "KR"},
				{code: "L",   label: "L"},
				{code: "LW",  label: "LW"},
				{code: "ML",  label: "ML"},
				{code: "MS",  label: "MS"},
				{code: "P",   label: "P"},
				{code: "Pe",  label: "Pe"},
				{code: "Ps",  label: "Ps"},
				{code: "Ro",  label: "Ro"},
				{code: "Ry",  label: "Ry"},
				{code: "Sb",  label: "Sb"},
				{code: "Te",  label: "Te"},
				{code: "Th",  label: "Th"},
				{code: "TPM", label: "TPM"},
				{code: "VA",  label: "VA"},
				{code: "VB",  label: "VB"},
				{code: "VC",  label: "VC"},
				{code: "VD",  label: "VD"},
				{code: "We",  label: "We"}
			];
			
			return result;
		}
		

	}
}