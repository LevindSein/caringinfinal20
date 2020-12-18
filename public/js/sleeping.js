function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function backtopage() {
    console.log('Taking a break...');
    await sleep(2000);

    // Sleep in loop
    for (let i = 0; i < 3; i++) {
        if (i === 3)
        await sleep(2000);
        console.log(i);
    }
    window.history.back();
}

backtopage();