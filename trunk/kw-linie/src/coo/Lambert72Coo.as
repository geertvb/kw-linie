package coo{
	import flash.geom.Point;
	
	
	public class Lambert72Coo {
		public var a: Number = 6378388.0;
		public var f: Number = 1.0 / 297.0;
		public var phi0: Number = 90.0 * Math.PI / 180.0;
		public var phi1: Number = (49.0 + (50.0 / 60.0) + (0.00204 / 3600.0)) * Math.PI / 180.0; 
		public var phi2: Number = (51.0 + (10.0 / 60.0) + (0.00204 / 3600.0)) * Math.PI / 180.0;
		public var x0: Number = 150000.013;
		public var y0: Number = 5400088.438;
		public var lambda0: Number = (4.0 + (22.0 / 60.0) + (2.952 / 3600.0)) * Math.PI / 180.0;
		
		public var e: Number; 
		public var m1: Number;
		public var m2: Number;
		public var t0: Number;
		public var t1: Number;
		public var t2: Number;
		public var n: Number;
		public var g: Number;
		public var r0: Number;
		
		public function ln(value:Number) : Number {
			return Math.log(value);
		}
		
		public function Lambert72Coo() {
			this.e = Math.sqrt(2 * this.f - Math.pow(this.f, 2));
			this.m1 = Math.cos(this.phi1) / Math.sqrt(1 - Math.pow(this.e,2) * Math.pow(Math.sin(this.phi1),2));
			this.m2 = Math.cos(this.phi2) / Math.sqrt(1 - Math.pow(this.e,2) * Math.pow(Math.sin(this.phi2),2));
			this.t0 = Math.tan(Math.PI/4 - this.phi0/2) / Math.pow((1 - this.e * Math.sin(this.phi0))/(1 + this.e * Math.sin(this.phi0)),this.e/2);
			this.t1 = Math.tan(Math.PI/4 - this.phi1/2) / Math.pow((1 - this.e * Math.sin(this.phi1))/(1 + this.e * Math.sin(this.phi1)),this.e/2);
			this.t2 = Math.tan(Math.PI/4 - this.phi2/2) / Math.pow((1 - this.e * Math.sin(this.phi2))/(1 + this.e * Math.sin(this.phi2)),this.e/2);
			this.n = (this.ln(this.m1)-this.ln(this.m2)) / (this.ln(this.t1) - this.ln(this.t2));
			this.g = this.m1 / (this.n * Math.pow(this.t1, this.n));
			this.r0 = this.a * this.g * Math.pow(this.t0, this.n);			
		}
		
		public function convert(x:Number, y:Number) : Point {
			var r: Number = Math.sqrt(Math.pow(x - this.x0, 2) + Math.pow(this.r0 - (y - this.y0),2));
			var t: Number = Math.pow(r / (this.a * this.g), 1 / this.n);
			var theta: Number = Math.atan((x - x0) / (r0 - (y - y0)));
			var lambda: Number = (theta / this.n) + this.lambda0;
			var currentPhi: Number = Math.PI/2 - 2 * Math.atan(t);
			for (var i:Number=0; i<10; i++) {
				var previousPhi: Number = currentPhi;
				currentPhi = Math.PI/2 - 2 * Math.atan(t * Math.pow((1 - this.e * Math.sin(previousPhi)) / (1 + this.e * Math.sin(previousPhi)), this.e/2));
			}
			trace("lambda " + (lambda * 180 / Math.PI));
			trace("phi    " + (currentPhi * 180 / Math.PI));
			return new Point(lambda, currentPhi);
		}
	}

}