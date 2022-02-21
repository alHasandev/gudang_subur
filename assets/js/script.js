// Returns the ISO week of the date.
Date.prototype.getWeek = function () {
	var date = new Date(this.getTime());
	date.setHours(0, 0, 0, 0);
	// Thursday in current week decides the year.
	date.setDate(date.getDate() + 3 - ((date.getDay() + 6) % 7));
	// January 4 is always in week 1.
	var week1 = new Date(date.getFullYear(), 0, 4);
	// Adjust to Thursday in week 1 and count number of weeks from date to week1.
	return (
		1 +
		Math.round(
			((date.getTime() - week1.getTime()) / 86400000 -
				3 +
				((week1.getDay() + 6) % 7)) /
				7
		)
	);
};

function padLeadingZero(num, size = 2) {
	let strNum = num + "";
	while (strNum.length < size) {
		strNum = "0" + strNum;
	}

	return strNum;
}

function getWeekDate(day = 0, weekNumber, year) {
	const firstDayOfYear = new Date("" + year + "");
	const numOfdaysPastSinceLastMonday = firstDayOfYear.getDay() - 1;
	const dayNumber = weekNumber * 7 + numOfdaysPastSinceLastMonday;
	const date = new Date(year, 0, dayNumber + day);

	const dateObject = {
		year: date.getFullYear(),
		month: padLeadingZero(date.getMonth() + 1),
		date: padLeadingZero(date.getDate() - 1),
	};

	return `${dateObject.year}-${dateObject.month}-${dateObject.date}`;
}

function getDateRangeOfWeek(weekNo, year) {
	let dates = [];
	for (let i = 0; i < 7; i++) {
		let localDateString = moment()
			.day(i)
			.week(weekNo + 1)
			.format("YYYY-MM-DD");
		// let localDateString = getWeekDate(i, weekNo, year);
		dates.push(localDateString);
	}

	return dates;
}

const namaHari = [
	"Minggu",
	"Senin",
	"Selasa",
	"Rabu",
	"Kamis",
	"Jum'at",
	"Sabtu",
];
