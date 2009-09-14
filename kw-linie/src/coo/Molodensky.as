package coo
{
	public class Molodensky
	{
		public var sec2radians: Number = 60.0 * 60.0 * 180.0 / Math.PI;
		
		public var Tx: Number = -106.868628;
		public var Ty: Number = 52.297783;
		public var Tz: Number = -103.723893;
		
		public var Rx: Number = -0.336570 / sec2radians;
		public var Ry: Number = 0.456955 / sec2radians;
		public var Rz: Number = -1.842183 / sec2radians;
		
		public var scale: Number = 1 / 1.0000012747;
		
		public var sx: Number = Math.sin(Rx);
		public var sy: Number = Math.sin(Ry);
		public var sz: Number = Math.sin(Rz);
		
		public var cx: Number = Math.cos(Rx);
		public var cy: Number = Math.cos(Ry);
		public var cz: Number = Math.cos(Rz);
		
		public function convert(x: Number, y: Number, z: Number) : Object {
			var nx: Number = Tx + scale * (  (cy * cz) * x + (cx * sz + sx * sy * cz) * y + (sx * sz - cx * sy * cz) * z);
			var ny: Number = Ty + scale * ( -(cy * sz) * x + (cx * cz - sx * sy * sz) * y + (sx * cz + cx * sy * sz) * z);
			var nz: Number = Tz + scale * (         sy * x -                (sx * cy) * y +                (cx * cy) * z);
			return {x: nx, y: ny, z: nz};
		} 

		
	}
}