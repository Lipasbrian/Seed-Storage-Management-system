<?php
// bins.php - Bins Management
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();
// Fetch bins
$stmt = $db->query('SELECT b.*, sv.variety_name FROM bins b LEFT JOIN seed_varieties sv ON b.assigned_variety_id = sv.id ORDER BY b.bin_number');
$bins = $stmt->fetchAll();
include 'includes/header.php';
?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Bins Management</h1>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h5>All Bins (1-48)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr data-bin-number="<?php echo $bin['bin_number']; ?>">
                                <th>Bin Number</th>
                                <th>Capacity</th>
                                <th>Current Stock (kg)</th>
                                <th>Status</th>
                                <th>Variety</th>
                                <th>Moisture Content (%)</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Bag / capacity constants
                                $BAG_WEIGHT_KG = 60;
                                $MAX_BAGS = 300;
                                $DEFAULT_CAPACITY_KG = $BAG_WEIGHT_KG * $MAX_BAGS; // 18,000 kg
                                foreach($bins as $bin):
                                    // Use stored capacity if present, otherwise default to 300 bags (60kg each)
                                    $capacityKg = (!empty($bin['capacity_kg']) && $bin['capacity_kg'] > 0) ? $bin['capacity_kg'] : $DEFAULT_CAPACITY_KG;
                                    $capacityBags = round($capacityKg / $BAG_WEIGHT_KG);
                                    $currentKg = !empty($bin['current_stock_kg']) ? $bin['current_stock_kg'] : 0;
                                    $fillPercent = ($capacityKg > 0) ? min(100, ($currentKg / $capacityKg) * 100) : 0;
                                    // Determine status by fill percent
                                    if($currentKg <= 0) {
                                        $statusLabel = 'Empty';
                                        $statusClass = 'bg-success';
                                    } elseif($fillPercent >= 100) {
                                        $statusLabel = 'Full';
                                        $statusClass = 'bg-danger';
                                    } else {
                                        $statusLabel = 'Partial';
                                        $statusClass = 'bg-warning';
                                    }
                            ?>
                            <tr>
                                <td><?php echo $bin['bin_number']; ?></td>
                                <td>
                                    <?php echo number_format($capacityBags); ?> bags (<?php echo number_format($capacityKg); ?> kg)
                                </td>
                                <td><?php echo number_format($currentKg); ?></td>
                                <td>
                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                                    <div class="progress mt-2" style="height:8px; max-width:140px;">
                                        <div class="progress-bar" role="progressbar" style="width: <?php echo round($fillPercent); ?>%;" aria-valuenow="<?php echo round($fillPercent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($bin['variety_name'] ?? ''); ?></td>
                                <td><?php echo $bin['current_moisture_content']; ?></td>
                                <td><?php echo formatDate($bin['last_updated']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
        (function(){
            // Poll every 8 seconds for updated bin statuses
            const POLL_MS = 8000;
            async function refreshBins(){
                try{
                    const res = await fetch('ajax_get_bins_statuses.php');
                    if(!res.ok) return;
                    const bins = await res.json();
                    bins.forEach(b => {
                        const row = document.querySelector(`tr[data-bin-number="${b.bin_number}"]`);
                        if(!row) return;
                        // Update current stock cell (3rd column, index 2)
                        const curCell = row.cells[2];
                        curCell.textContent = Number(b.current_stock_kg).toLocaleString();
                        // Update status cell (4th column, index 3)
                        const statusCell = row.cells[3];
                        const statusClass = b.status === 'empty' ? 'bg-success' : (b.status === 'full' ? 'bg-danger' : 'bg-warning');
                        const statusHtml = `<span class="badge ${statusClass}">${b.status.charAt(0).toUpperCase() + b.status.slice(1)}</span>`;
                        const progress = `<div class="progress mt-2" style="height:8px; max-width:140px;"><div class="progress-bar" role="progressbar" style="width: ${Math.round(b.fill_percent)}%;" aria-valuenow="${Math.round(b.fill_percent)}" aria-valuemin="0" aria-valuemax="100"></div></div>`;
                        statusCell.innerHTML = statusHtml + progress;
                    });
                }catch(e){
                    console.error('Failed to refresh bins', e);
                }
            }
            // Initial load + interval
            refreshBins();
            setInterval(refreshBins, POLL_MS);
        })();
        </script>
<?php include 'includes/footer.php'; ?>
