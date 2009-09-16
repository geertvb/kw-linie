package referenceData
{
	public class AnderObjectTypeHolder
	{
		
		private static var holder: Object = createHolder();
		private static var holder2: Object = createHolder2();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{type: "ankerpaal"},
				{type: "brug"},
				{type: "sluis"},
				{type: "geassocieerd huis"},
				{type: "cointetelement"},
				{type: "loopgracht"},
				{type: "antitankgracht"},
				{type: "ander"}
			];
			
			return result;
		}
		
		public static function getInstance2() : Object {
			return holder2;
		}
		
		public static function createHolder2() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{type: null                ,label: "Alle"              },
				{type: "ankerpaal"         ,label: "ankerpaal"         },
				{type: "brug"              ,label: "brug"              },
				{type: "sluis"             ,label: "sluis"             },
				{type: "geassocieerd huis" ,label: "geassocieerd huis" },
				{type: "cointetelement"    ,label: "cointetelement"    },
				{type: "loopgracht"        ,label: "loopgracht"        },
				{type: "antitankgracht"    ,label: "antitankgracht"    },
				{type: "ander"             ,label: "ander"             },
			];
			
			return result;
		}
		
	}
}