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
				{code: "A",   label: "A: Kanaal Mechelen - Leuven (1e lijn)"},
				{code: "Bb",  label: "Bb: Bois de Beumont (Wavre)"},
				{code: "BL",  label: "BL: Wavre Zuid (1e lijn)"},
				{code: "C",   label: "C: Kanaal Mechelen - Leuven (2e lijn)"},
				{code: "Do",  label: "Do: Doren"},
				{code: "F",   label: "F: Spoorweg Leuven (1e lijn)"},
				{code: "GH",  label: "GH: Gasthuisbos"},
				{code: "H",   label: "H: Haacht - Kanaal (1e lijn)"},
				{code: "Ha",  label: "Ha: Haacht"},
				{code: "He",  label: "He: Herent"},
				{code: "Ib",  label: "Ib: Itterbeek - Blauwenhoek"},
				{code: "KO",  label: "KO: Hoogstraat"},
				{code: "KR",  label: "KR: Bonheiden"},
				{code: "L",   label: "L: Lier - Haacht (1e lijn)"},
				{code: "LW",  label: "LW: Leuven - Wavre"},
				{code: "ML",  label: "ML: Massenhoven - Lier"},
				{code: "MS",  label: "MS: Maison de Sante (Leuven)"},
				{code: "P",   label: "P: Lier - Kanaal (2e lijn)"},
				{code: "Pe",  label: "Pe: Peulis"},
				{code: "Ps",  label: "Ps: Peulis"},
				{code: "Ro",  label: "Ro: Roesselberg"},
				{code: "Ry",  label: "Ry: Rymenam"},
				{code: "Sb",  label: "Sb: Wavre"},
				{code: "Te",  label: "Te: Terbank"},
				{code: "Th",  label: "Th: Tildonk"},
				{code: "TPM", label: "TPM: Bruggenhoofd Mechelen"},
				{code: "VA",  label: "VA: Connectiekamer 1e lijn"},
				{code: "VB",  label: "VB: Connectiekamer 2e lijn"},
				{code: "VC",  label: "VC: Connectiekamer dwarsverbinding"},
				{code: "VD",  label: "VD: Connectiekamer achterwaartse verbinding"},
				{code: "We",  label: "We: Wespelaar"}
			];
			
			return result;
		}
		

	}
}