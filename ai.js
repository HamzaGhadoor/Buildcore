document.getElementById('estimateForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const rooms = parseInt(document.getElementById('rooms').value);
    const wallHeight = parseFloat(document.getElementById('wallHeight').value);
    const wallWidth = parseFloat(document.getElementById('wallWidth').value);
    const wallThickness = parseFloat(document.getElementById('wallThickness').value);
    const materialType = document.getElementById('material').value === "bricks" ? 0 : 1;
    const doors = parseInt(document.getElementById('doors').value) || 0;
    const windows = parseInt(document.getElementById('windows').value) || 0;

    if (!rooms || !wallHeight || !wallWidth || !wallThickness) {
        alert('Please fill in all the fields.');
        return;
    }

    const input = [rooms, wallHeight, wallWidth, wallThickness, materialType, doors, windows];
    const estimation = await predictMaterial(input);
    displayResults(estimation);
});

async function createModel() {
    const model = tf.sequential();
    model.add(tf.layers.dense({ inputShape: [6], units: 16, activation: 'relu' }));
    model.add(tf.layers.dense({ units: 16, activation: 'relu' }));
    model.add(tf.layers.dense({ units: 5, activation: 'linear' }));

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

async function predictMaterial(input) {
    const model = await createModel();
    await trainModel(model, [[2, 10, 12, 0.5, 1, 1, 2], [3, 12, 14, 0.6, 0, 2, 3]], [[5000, 20, 50, 10, 70000], [3000, 15, 40, 8, 50000]]);
    const prediction = model.predict(tf.tensor2d([input]));
    const values = await prediction.data();

    return {
        totalMaterials: Math.ceil(values[0]),
        cementBags: Math.ceil(values[1]),
        sand: Math.ceil(values[2]),
        ironRods: Math.ceil(values[3]),
        totalCost: Math.ceil(values[4])
    };
}

function displayResults(estimation) {
    const modalBody = document.getElementById('resultModalBody');
    modalBody.innerHTML = `
        <h4>Estimated Materials:</h4>
        <p><strong>Materials:</strong> ${estimation.totalMaterials} units</p>
        <p><strong>Cement Bags:</strong> ${estimation.cementBags} bags</p>
        <p><strong>Sand:</strong> ${estimation.sand} cubic feet</p>
        <p><strong>Iron Rods:</strong> ${estimation.ironRods} rods</p>
        <p><strong>Total Cost:</strong> ₨${estimation.totalCost}</p>
    `;
    const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
    resultModal.show();
}

$('#material').change(function() {
    let material = $(this).val();
    $('#materialImage').attr('src', `images/${material}.png`);
    gsap.from('#materialImage', { scale: 0.5, opacity: 0, duration: 0.5 });
});
