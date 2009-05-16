package referenceData
{
	public class AanwezigheidHolder
	{

		private static var holder : Object = createHolder();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{aanwezigheid: "Ja"},
				{aanwezigheid: "Niet zeker"},
				{aanwezigheid: "Neen"}
			];
			
			return result;
		}

	}

}