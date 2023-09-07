import { Chart } from 'chart.js';

Number.prototype.toFixed = function (digits) {
    const pow = Math.pow(10, digits),
        fixed = (Math.round(this * pow) / pow).toString();

    if (!fixed.includes('.')) return fixed;

    return fixed.replace(/\.?0+$/, '');
};

function _left(chart, index) {
    if (!index || index === 0) {
        return chart.chartArea.left;
    }

    const meta = chart.getDatasetMeta(0),
        model1 = meta.data[index - 1]._model,
        model2 = meta.data[index]._model;

    return (model1.x + model2.x) / 2;
}

function _right(chart, index) {
    const meta = chart.getDatasetMeta(0);

    if (!index || index === meta.data.length - 1) {
        return chart.chartArea.right;
    }

    const model1 = meta.data[index]._model,
        model2 = meta.data[index + 1]._model;

    return (model1.x + model2.x) / 2;
}

function _resolvePosition(chart, position) {
    const { from, to } = position;

    const left = _left(chart, from),
        right = _right(chart, to);

    return {
        x: _left(chart, from),
        y: chart.chartArea.top,
        width: right - left,
        height: chart.chartArea.bottom - chart.chartArea.top
    }
}

Chart.plugins.register({
    id: 'highlights',
    beforeDraw: function (chart, args, options) {
        if (!options || !Array.isArray(options) || options.length === 0) return;

        const { ctx } = chart;

        ctx.save();

        for (const area of options) {
            const position = _resolvePosition(chart, area),
                color = area.color || 'rgba(255, 0, 0, 0.5)';

            ctx.globalCompositeOperation = 'destination-over';
            ctx.fillStyle = color;
            ctx.fillRect(position.x, position.y, position.width, position.height);
        }

        ctx.restore();
    }
});