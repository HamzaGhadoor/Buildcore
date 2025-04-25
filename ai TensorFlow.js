async function createModel() {
    const model = tf.sequential();
    
    model.add(tf.layers.dense({ inputShape: [4], units: 16, activation: 'relu' }));
    model.add(tf.layers.dense({ units: 16, activation: 'relu' }));
    model.add(tf.layers.dense({ units: 5, activation: 'linear' })); // Output: materials, cement, sand, iron, cost

    model.compile({
        optimizer: tf.train.adam(),
        loss: 'meanSquaredError',
        metrics: ['mse']
    });

    return model;
}
async function trainModel(model, inputs, labels) {
    const xs = tf.tensor2d(inputs);
    const ys = tf.tensor2d(labels);

    await model.fit(xs, ys, {
        epochs: 50,
        batchSize: 16,
        validationSplit: 0.2,
        shuffle: true
    });

    console.log("Training complete");
}
document.getElementById('estimateForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const rooms = parseInt(document.getElementById('rooms').value);
    const wallHeight = parseFloat(document.getElementById('wallHeight').value);
    const wallWidth = parseFloat(document.getElementById('wallWidth').value);
    const wallThickness = parseFloat(document.getElementById('wallThickness').value);
    const materialType = document.getElementById('material').value === "bricks" ? 0 : 1;

    const input = [wallHeight, wallWidth, wallThickness, materialType];

    const estimation = await predictMaterial(model, input);

    displayResults(estimation);
});
