const historyList = document.querySelector('.history__list');

const firstNumberEl = document.querySelector('#first-number');
const secondNumberEl = document.querySelector('#second-number');
const operationEl = document.querySelector('#op');

historyList.addEventListener('click', (e) => {
	const target = e.target;

	if(target.classList.contains('history__list-copy')) {
		const info = target.previousElementSibling;
		const result = info.querySelector('.history__list-result');

		const splitted = result.textContent.split(' ').slice(0,3);
		const calcInfo = {
			a: splitted[0],
			b: splitted[2],
			op: splitted[1]
		}

		firstNumberEl.value = calcInfo.a;
		secondNumberEl.value = calcInfo.b;
		operationEl.value = calcInfo.op;
	}
})