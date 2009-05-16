package referenceData
{
	public class RedenNietAanwezigHolder
	{

		private static var holder : Object = createHolder();
		
		public static function getInstance() : Object {
			return holder;
		}
		
		public static function createHolder() : Object {
			var result: Object;
			
			result = new Object();
			result.data = [
				{reden_niet_aanwezig: "Afgebroken"},
				{reden_niet_aanwezig: "Verzonken"},
				{reden_niet_aanwezig: "Andere"}
			];
			
			return result;
		}

	}

}