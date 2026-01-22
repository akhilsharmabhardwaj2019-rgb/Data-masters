// Simple animation message
window.onload = function () {
    console.log("Welcome to Bharat Data Recovery!");
    fetchReviews();
};

function fetchReviews() {
    const container = document.getElementById('reviews-container');
    if (!container) return; // Only run on home page

    fetch('../api/get_reviews.php')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                container.innerHTML = '';
                data.forEach(review => {
                    const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
                    const card = `
                        <div style="background: var(--bg-card); padding: 30px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                            <div style="color: gold; margin-bottom: 10px; font-size: 1.2rem;">${stars}</div>
                            <p style="color: #ddd; font-style: italic; margin-bottom: 15px;">"${review.comment}"</p>
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                                <span style="color: var(--primary); font-weight: 600;">${review.name}</span>
                                <span style="color: var(--text-dim); font-size: 0.8rem;">${review.date}</span>
                            </div>
                        </div>
                    `;
                    container.innerHTML += card;
                });
            } else {
                container.innerHTML = '<p style="text-align: center; color: var(--text-dim); grid-column: 1/-1;">No reviews yet. Be the first to share your experience!</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching reviews:', error);
            container.innerHTML = '<p style="text-align: center; color: var(--text-dim); grid-column: 1/-1;">Unable to load reviews at this time.</p>';
        });
}
